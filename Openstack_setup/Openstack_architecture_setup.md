# Setup Openstack for hybrid cloud AWS & Openstack
## Network Topology for openstack
![Openstack topology](/Openstack_setup/picture/Openstack_topology.png)
## Basic OpenStack Setup

This section covers the **initial OpenStack networking, security, and instance setup** required for the hybrid cloud environment.

---

## Create Networks and Subnets

At least **two networks** are required:
- **DMZ network** (public-facing / gateway)
- **Private network** (backend services)

---

## DMZ Network
```bash
openstack network create dmz-net
openstack subnet create dmz-subnet --network dmz-net --subnet-range 192.168.10.0/24 --gateway 192.168.10.1 --allocation-pool start=192.168.10.2,end=192.168.10.253 --dns-nameserver 8.8.8.8
```

## Private network
```bash
openstack network create private-net
openstack subnet create private-subnet --network private-net --subnet-range 192.168.20.0/24 --dns-nameserver 8.8.8.8
```

## Create flavor for the instance
```bash
openstack flavor create m1.small  --ram 2048--disk 10 --vcpus 1
openstack flavor create backend.small --ram 1024 --disk 10 --vcpus 1
```
## Create image for the instances
```bash
openstack image create "Ubuntu-22.04-server" --file Ubuntu-22.04-server-server-cloudimg-amd64.img  --disk-format qcow2 --container-format bare --public
```
## Security rules
### Security rules for R2R 
```bash
openstack security group create R2R-SG
```
Ingress rule
```bash
openstack security group rule create R2R-SG --ingress --ethertype IPv4 --protocol icmp --remote-ip 192.168.20.0/24

openstack security group rule create R2R-SG --ingress --ethertype IPv4 --protocol tcp --dst-port 22 --remote-ip 0.0.0.0/0

openstack security group rule create R2R-SG --ingress --ethertype IPv4 --protocol icmp --remote-ip 0.0.0.0/0

openstack security group rule create R2R-SG --ingress --ethertype IPv4 --protocol udp --remote-ip 192.168.20.0/24

openstack security group rule create R2R-SG --ingress --ethertype IPv4 --protocol tcp --remote-ip 192.168.20.0/24

openstack security group rule create R2R-SG --ingress --ethertype IPv4 --protocol udp --dst-port 51820 --remote-ip 0.0.0.0/0
```
Engress rule
```bash
openstack security group rule create R2R-SG --egress --ethertype IPv4

openstack security group rule create R2R-SG --egress --ethertype IPv6

openstack security group rule create R2R-SG --egress --ethertype IPv4 --protocol tcp --dst-port 22 --remote-ip 192.168.20.0/24
```

### Security rules for db-sg
```bash
openstack security group create db-sg
```
Ingress rule
```bash
openstack security group rule create db-sg --ingress --ethertype IPv4 --protocol icmp --remote-ip 192.168.20.0/24

openstack security group rule create db-sg --ingress --ethertype IPv4 --protocol tcp --dst-port 22 --remote-ip 192.168.20.0/24

openstack security group rule create db-sg --ingress --ethertype IPv4 --protocol tcp --dst-port 3306 --remote-ip 192.168.20.0/24
```
Engress rule
```bash
openstack security group rule create db-sg --egress --ethertype IPv4 --remote-ip 0.0.0.0/0

openstack security group rule create db-sg --egress --ethertype IPv4 --protocol icmp --remote-ip 192.168.20.250/32

openstack security group rule create db-sg --egress --ethertype IPv4 --remote-ip 192.168.20.250/32
```

## Create instance
### R2R Instance
```bash
openstack server create --image Ubuntu-22.04-server --flavor m1.small --network dmz-net --key-name fw-access --security-group R2R-SG R2R
```

### mysql-server1 Instance
```bash
openstack server create --image Ubuntu-22.04-server --flavor backend.small --network private-net --key-name fw-access --security-group db-sg  mysql-server1
```

## Attach private to R2R vm
```bash
openstack server add network R2R private-net
```

SSh to the R2R
Turn on the current down interface, could be different name
```bash
sudo ip link set ens7 up
sudo dhclient ens7
```

## WireGuard Setup on Openstack (R2R Instance)

---

### 1. Update system and install WireGuard
Install WireGuard
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install wireguard -y
```
Create get the private key and public key on Openstack Wireguard
```bash
sudo -i
cd /etc/wireguard/
umask 077
wg genkey | tee privatekey | wg pubkey > publickey
echo "Private Key of Openstack:" && cat privatekey
echo "Public Key of Openstack:" && cat publickey
```

Create a configure file for wireguard
```bash
sudo nano /etc/wireguard/wg0.conf 
```

Add the following config
```bash
[Interface]
Address = 10.10.0.2/24
ListenPort = 51820
PrivateKey = <Openstack Wireguard private key>


[Peer]
PublicKey = <AWS Wireguard public key>
Endpoint = 13.214.243.204:51820
AllowedIPs = 10.10.0.1/32, 10.0.0.0/20
PersistentKeepalive = 25
```

## Add snat route to R2R vm mysql connection for aws
```bash
sudo iptables -A FORWARD -i wg0 -o ens7 -d 192.168.20.0/24 -j ACCEPT

sudo iptables -A FORWARD -i ens7 -o wg0 -m state --state RELATED,ESTABLISHED -j ACCEPT

sudo iptables -t nat -A POSTROUTING -s 10.10.0.0/24 -d 192.168.20.0/24  -o ens7  -j SNAT --to-source 192.168.20.250
```
## Add route to mysql vm

```bash
sudo ip route add default via 192.168.20.250
```
## Install service logstash on R2R
Download and edit the .conf file like the on here
```bash
wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo gpg --dearmor -o /usr/share/keyrings/elasticsearch-keyring.gpg

echo "deb [signed-by=/usr/share/keyrings/elasticsearch-keyring.gpg] https://artifacts.elastic.co/packages/8.x/apt stable main" | sudo tee /etc/apt/sources.list.d/elastic-8.x.list

sudo apt update
sudo apt install logstash -y
sudo nano /etc/logstash/conf.d/01-beats-routing.conf
```

Reset logstash
```bash
sudo mv /etc/logstash/conf.d/db-forward.conf /etc/logstash/conf.d/db-forward.conf.bak
sudo mv /etc/logstash/conf.d/web-forward.conf /etc/logstash/conf.d/web-forward.conf.bak

sudo systemctl restart logstash
sudo journalctl -u logstash -f
```

## Install service mysql and filebeat on mysql-server1

```bash
curl -fsSL https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo gpg --dearmor -o /usr/share/keyrings/elastic-keyring.gpg
echo "deb [signed-by=/usr/share/keyrings/elastic-keyring.gpg] \
https://artifacts.elastic.co/packages/8.x/apt stable main" | sudo tee /etc/apt/sources.list.d/elastic-8.x.list

sudo apt update
sudo apt install -y mysql-server filebeat
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```
Create a user and database
```bash
sudo mysql -u root -p
```

```bash
CREATE USER 'awsuser'@'<Openstack VPN private-net NIC ip>' IDENTIFIED BY '<Password>';

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(50)
);

INSERT INTO users VALUES
(NULL, 'admin', 'admin123'),
(NULL, 'test', 'test123'),
(NULL, 'user1', 'password');

ALTER TABLE users ADD role VARCHAR(10);

UPDATE users SET role='admin' WHERE username='admin';
UPDATE users SET role='user' WHERE role IS NULL;


CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50),
  price INT
);

INSERT INTO products VALUES
(1,'Laptop',1200),
(2,'Phone',800),
(3,'Headphones',150);
```

Configure filebeat

```bash
sudo nano /etc/filebeat/filebeat.yml 
```