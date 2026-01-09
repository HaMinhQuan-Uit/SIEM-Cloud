# Setup AWS for hybrid cloud AWS & Openstack
## Network Topology for AWS
![AWS topology](/AWS_setup/picture/AWS_network_topology.png)
## Basic setup
## Create VPC (AWS Public Cloud)

Follow the steps below to create the AWS VPC used for the hybrid cloud setup.

---

### Step 1: Open VPC Creation Wizard

1. Go to **AWS Management Console**
2. Navigate to **VPC**
3. Click **Create VPC**
4. Select **VPC and more**

---

### Step 2: VPC Configuration

| Setting | Value |
|------|------|
| **VPC Name** | `aws-public-cloud` |
| **IPv4 CIDR Block** | `10.0.0.0/16` |
| **Enable Public IP** | Enabled |
| **DNS Resolution** | Enabled |
| **DNS Hostnames** | Enabled |

---

### Step 3: Availability Zones & Subnets

| Setting | Value |
|------|------|
| **Number of Availability Zones** | 1 |
| **Public Subnets** | 1 |
| **Private Subnets** | 1 |

#### Subnet Details

**Public Subnet**
- CIDR block: `10.0.0.0/20`
- Auto-assign public IPv4 address: **Enabled**

**Private Subnet**
- CIDR block: `10.0.128.0/20`

---

### Step 4: NAT Gateway Configuration

| Setting | Value |
|------|------|
| **NAT Gateway** | Enabled |
| **NAT Type** | Zonal |
| **Availability Zone** | Same AZ as subnets |

> This allows instances in the private subnet to access the internet while remaining private.

---

### Step 5: Additional Settings

| Setting | Value |
|------|------|
| **S3 Gateway Endpoint** | Disabled |
| **Other VPC Endpoints** | None |

---

### Step 6: Create VPC

1. Review the configuration
2. Click **Create VPC**
3. Wait until AWS finishes provisioning resources

---

### Result

After creation, AWS automatically provisions:
- 1 VPC (`aws-public-cloud`)
- 1 Public Subnet
- 1 Private Subnet
- 1 Internet Gateway
- 1 NAT Gateway
- Proper route table associations


## Create R2R EC2 Instance (Router-to-Router / VPN Gateway)

This instance acts as the **gateway/router** between AWS and the private network.

---

### Step 1: Launch EC2 Instance

1. Go to **EC2 → Launch Instance**
2. Set the following options:

| Setting | Value |
|------|------|
| **Instance Name** | `R2R` |
| **AMI** | Ubuntu 24.04 LTS |
| **Network (VPC)** | `aws-public-cloud` |
| **Subnet** | Public subnet (`10.0.0.0/20`) |
| **Auto-assign Public IP** | Enabled |

---

### Step 2: Security Group (`AWS-VPN-SG`)

Create or select a security group with the following inbound rules:

| Type | Protocol | Port | Source |
|----|----|----|----|
| All ICMP - IPv4 | ICMP | All | `0.0.0.0/0` |
| Custom TCP | TCP | 5044 | `10.0.128.0/20` |
| SSH | TCP | 22 | `0.0.0.0/0` |
| MySQL/Aurora | TCP | 3306 | `10.0.128.0/20` |
| Custom UDP | UDP | 51820 | `0.0.0.0/0` |

> Port **51820/UDP** is used for **WireGuard VPN**.

---

### Step 3: Launch the Instance
1. Review the configuration
2. Click **Launch Instance**
3. Wait until the instance is running

---

### Step 4: Allocate & Associate Elastic IP

1. Go to **EC2 → Elastic IPs**
2. Click **Allocate Elastic IP**
3. Select the newly created Elastic IP
4. Click **Associate Elastic IP**
5. Associate it with the **R2R instance**

> Save this **Elastic Public IP** — it will be used for VPN and external access.

---

### Step 5: Disable Source/Destination Check

Since this instance acts as a router:

1. Go to **EC2 → Instances → R2R**
2. Select **Networking**
3. Click **Change source/destination check**
4. **Disable** it

---

## Create Website EC2 Instance (Private)

This instance hosts the website and runs entirely in the **private subnet**.

---

### Step 1: Launch EC2 Instance

| Setting | Value |
|------|------|
| **Instance Name** | `Website` |
| **AMI** | Ubuntu 24.04 LTS |
| **Network (VPC)** | `aws-public-cloud` |
| **Subnet** | Private subnet (`10.0.128.0/20`) |
| **Auto-assign Public IP** | Disabled |

---

### Step 2: Security Group (`Website-sg`)

Inbound rules:

| Type | Protocol | Port | Source |
|----|----|----|----|
| HTTP | TCP | 80 | `0.0.0.0/0` |
| HTTPS | TCP | 443 | `0.0.0.0/0` |
| SSH | TCP | 22 | `10.0.0.0/20` |
| HTTP | TCP | 80 | `10.0.0.0/20` |
| HTTPS | TCP | 443 | `10.0.0.0/20` |

> SSH access is **only allowed from the public subnet** via the R2R instance.

---

### Step 3: Launch the Instance
1. Review settings
2. Click **Launch Instance**
3. Confirm the instance is running

---

## Route Table Configuration (Hybrid Routing)

Update the route table associated with the **private subnet**.

### Routes

| Destination | Target |
|-----------|--------|
| `0.0.0.0/0` | `igw-0900a498b4ce44c15` |
| `10.0.0.0/16` | `local` |
| `192.168.10.0/24` | `eni-00d3456bdcbbf5732` |
| `192.168.20.0/24` | `eni-00d3456bdcbbf5732` |

> `eni-00d3456bdcbbf5732` is the **network interface of the R2R instance**, enabling traffic to reach OpenStack networks.

---

## Resulting Architecture

- **R2R EC2**
  - Public subnet
  - Elastic IP
  - VPN gateway & router
- **Website EC2**
  - Private subnet
  - No public IP
  - Internet access via R2R
- **Hybrid routing**
  - AWS ↔ OpenStack via R2R ENI

This completes the AWS-side EC2 setup for the **hybrid cloud architecture**.

## WireGuard Setup on AWS (R2R Instance)

---

### 1. Update system and install WireGuard
Install WireGuard
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install wireguard -y
```
Create get the private key and public key on AWS Wireguard
```bash
sudo -i
cd /etc/wireguard/
umask 077
wg genkey | tee privatekey | wg pubkey > publickey
echo "Private Key of AWS:" && cat privatekey
echo "Public Key of AWS:" && cat publickey
```

Create a configure file for wireguard
```bash
sudo nano /etc/wireguard/wg0.conf 
```

Add the following config
```bash
[Interface]
Address = 10.10.0.1/24
ListenPort = 51820
PrivateKey = <AWS Wireguard private key>


PostUp   = iptables -A FORWARD -i wg0 -j ACCEPT; iptables -A FORWARD -o wg0 -j ACCEPT; iptables -t nat -A POSTROUTING -o ens5 -j MASQUERADE
PostDown = iptables -D FORWARD -i wg0 -j ACCEPT; iptables -D FORWARD -o wg0 -j ACCEPT; iptables -t nat -D POSTROUTING -o ens5 -j MASQUERADE

[Peer]
PublicKey = <Openstack Wireguard public key>
AllowedIPs = 10.10.0.2/32, 192.168.10.0/24, 192.168.20.0/24
```
## Add SNAT route to R2R Instance
Add this Snat configure for website access to database
```bash
sudo iptables -t nat -A POSTROUTING -s 10.0.128.0/20 -d 192.168.20.0/24 -o wg0 -j SNAT --to-source 10.10.0.1

sudo sysctl -w net.ipv4.ip_forward=1
sudo sysctl -w net.ipv4.conf.all.rp_filter=0

sudo iptables -A FORWARD -s 10.0.128.0/20 -d 192.168.20.0/24 -j ACCEPT
sudo iptables -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT
```

## Install PHP and nginx on website instance
```bash
sudo apt update
sudo apt install nginx php-fpm php-mysql mariadb-client -y
sudo systemctl enable php8.3-fpm –now
```
Configure file
```bash
sudo nano /etc/php/8.3/fpm/php-fpm.conf 
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
```
Configure nginx.conf to send log and default site 
```bash
sudo nano /etc/nginx/nginx.conf
sudo nano /etc/nginx/sites-available/default
sudo nginx -t
sudo systemctl reload nginx
```

## Install Filebeat
```bash
url -fsSL https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo gpg --dearmor -o /usr/share/keyrings/elastic-keyring.gpg
echo "deb [signed-by=/usr/share/keyrings/elastic-keyring.gpg] \
https://artifacts.elastic.co/packages/8.x/apt stable main" | sudo tee /etc/apt/sources.list.d/elastic-8.x.list

sudo apt update
sudo apt install -y filebeat

sudo nano /etc/filebeat/filebeat.yml 
```