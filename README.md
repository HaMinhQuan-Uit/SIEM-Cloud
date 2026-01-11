# Hybrid Cloud SIEM Deployment with ELK Stack

**Topic:** Deploying SIEMs using the ELK Stack in a Hybrid Cloud with OpenStack and AWS  
**Institution:** University of Information Technology (UIT) - VNU-HCM  
**Course:** Cloud Computing Final Project

## 📖 Abstract

This project implements a Security Information and Event Management (SIEM) framework using the **ELK Stack** (Elasticsearch, Logstash, Kibana) to monitor a Hybrid Cloud environment. The infrastructure integrates **OpenStack** (Private Cloud) and **Amazon Web Services** (Public Cloud), connected securely via a **WireGuard VPN**. The system centralizes logs from web servers, database servers, and network devices to detect security threats in near real-time.

## 🏗 Architecture

[cite_start]The system consists of two main data flows[cite: 110]:
1.  **Application Flow:** Users access a Web Server (AWS EC2) which connects to a Database Server (OpenStack Instance) via a site-to-site VPN.
2.  **SIEM Flow:** Filebeat collects logs from both clouds, sends them to Logstash (on the VPN gateway), which normalizes and pushes data to Elasticsearch for visualization in Kibana.

![System Architecture](./images/architecrute.png)



## 🚀 Tech Stack

* **Cloud Platforms:** OpenStack, AWS (EC2, VPC).
* **Networking:** WireGuard VPN, IPTables (NAT/Routing).
* **SIEM Core:** Elasticsearch 7.x/8.x, Logstash, Kibana.
* **Data Collection:** Filebeat.
* **Application:** Nginx (Web Server), PHP-FPM, MySQL (Database).

## 🛠️ Network Topology

### [cite_start]OpenStack (Private Cloud) [cite: 185]
* **Public Network:** External access.
* **DMZ Network (`192.168.10.0/24`):** Hosts the VPN Gateway (R2R) and Logstash.
* **Private Network (`192.168.20.0/24`):** Hosts the sensitive Database Server.

### [cite_start]AWS (Public Cloud) [cite: 420]
* **VPC CIDR:** `10.0.0.0/16`
* **Public Subnet (`10.0.0.0/20`):** Hosts the VPN Gateway and Web Server.
* **Private Subnet (`10.0.128.0/20`):** Internal resources.

## 🔧 Installation & Setup

### 1. Hybrid Cloud Connectivity (VPN)
We used **WireGuard** to bridge AWS and OpenStack.
* [cite_start]**OpenStack R2R Node:** Acts as the VPN server and NAT gateway for the database[cite: 314].
* **AWS R2R Node:** Connects to OpenStack and routes traffic from the Web Server.

**Sample WireGuard Config (`wg0.conf`):**
```ini
[Interface]
Address = 10.10.0.2/24
ListenPort = 51820
PrivateKey = <Private_Key>

[Peer]
PublicKey = <Peer_Public_Key>
Endpoint = <Public_IP>:51820
AllowedIPs = 10.10.0.1/32, 10.0.0.0/20