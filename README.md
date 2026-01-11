Here is the translation of the document into English, formatted in Markdown as requested.

---

RESEARCH PROPOSAL: FINAL PROJECT REPORT - CLOUD COMPUTING 

**TOPIC:** DEPLOYING SIEMS USING THE ELK STACK IN A HYBRID CLOUD WITH OPENSTACK AND AWS 

**INSTRUCTOR:** PHD NGUYEN NGOC TU 

**STUDENTS:** 

* HA MINH QUAN - 22521177
* TU CHI KIEN - 22520713

---

1. Abstract 

The rapid adoption of hybrid cloud architectures, combining private cloud platforms like OpenStack with public cloud services like Amazon Web Services (AWS), has created significant challenges in security monitoring and incident detection due to the heterogeneity and dispersion of system logs. Traditional security monitoring methods often lack the ability to provide centralized visibility and real-time analysis across the entire hybrid cloud environment.

This paper presents the design and implementation of a Security Information and Event Management (SIEM) framework based on the ELK stack (Elasticsearch, Logstash, and Kibana) for security monitoring in an OpenStack-AWS hybrid cloud environment. The proposed framework enables centralized collection, normalization, indexing, and correlation of logs from multiple cloud components, including compute instances, network services, and system-level events.

The framework is deployed in an integrated OpenStack and AWS hybrid cloud test environment, where various security-related events and attack scenarios are generated to evaluate system performance. Experimental results show that the proposed ELK-based SIEM framework improves security visibility efficiency, supports near real-time threat detection, and provides scalable log analysis capabilities across the hybrid cloud infrastructure. The proposed solution offers a practical and scalable approach to enhance security monitoring in hybrid cloud environments and can serve as a foundation for advanced threat detection and incident response systems.

---

2. Introduction 

The rapid application of cloud computing has led organizations to increasingly deploy hybrid cloud architectures, combining private cloud platforms, such as OpenStack, with public cloud services like Amazon Web Services (AWS). Hybrid cloud environments offer greater flexibility, scalability, and cost-efficiency ; however, they also create significant challenges in security monitoring due to their distributed and heterogeneous nature.

In a hybrid cloud environment, security-related logs are generated from multiple layers, including virtual machines, cloud services, network components, and operating systems. These logs are often scattered across different platforms and formats, making centralized analysis and real-time threat detection difficult. As a result, security teams face limited visibility into security incidents, delayed response times, and an increased risk of Advanced Persistent Threats (APTs) that can operate across cloud boundaries.

Existing security monitoring solutions and commercial SIEM systems are often expensive, complex to deploy, and primarily designed for single cloud or on-premise infrastructures. Furthermore, many current research efforts focus on theoretical models or isolated cloud environments, leaving a gap in practical open-source SIEM frameworks specifically designed for OpenStack-AWS hybrid cloud deployments.

To address these challenges, this study proposes the design and implementation of a Security Information and Event Management (SIEM) framework based on the ELK stack (Elasticsearch, Logstash, and Kibana) for security monitoring in a hybrid cloud environment. The proposed framework allows for the centralized collection, normalization, indexing, and visualization of security event logs from both OpenStack and AWS infrastructures, providing unified visibility and improved threat detection capabilities.

**Key contributions of this work are summarized as follows:** 

* Implementation of a centralized application monitoring system to collect and analyze logs from multiple components.


* Establishment of a logging and incident management system using the Kibana interface for visualization and analysis.


* Normalization of raw log data to ensure consistency and enable efficient querying and event correlation.


* Deployment of a virtual lab environment to simulate attack scenarios and demonstrate the key functions of the ELK stack.



---

3. Problem Statement 

With the rapid development of cloud computing platforms, businesses are increasingly adopting hybrid cloud architectures, combining public cloud services with on-premise private cloud systems. While this deployment model offers flexibility and scalability, it also poses significant challenges in security monitoring and log management.

In a hybrid cloud environment, a large volume of logs is continuously generated from various sources, including system logs, network logs, server logs, application logs, and service-level logs. These logs are dispersed across multiple cloud platforms and infrastructure layers, making it difficult to achieve centralized visibility and effectively correlate security events. Furthermore, raw log data collected from different cloud platforms often follows heterogeneous formats and schemas. This lack of standardization prevents unified querying, correlation, and analysis of security events across the entire hybrid cloud environment. Consequently, identifying attack patterns, tracking incident timelines, and detecting advanced threats in a timely manner becomes a complex and error-prone task.

---

4. Overview 

4.1 Hybrid Cloud Model 

Hybrid cloud represents a cloud computing model that integrates private cloud infrastructure with public cloud services, enabling seamless sharing and integration of resources and services across both environments. In a hybrid cloud model, critical systems can be deployed and operated on on-premise infrastructure, while the flexible scalability of public cloud platforms can be leveraged to meet dynamic workload demands. Architecturally, a hybrid cloud environment typically consists of a private cloud deployed in an on-premise data center (e.g., OpenStack) and a public cloud platform (e.g., AWS). These environments are interconnected through secure networking mechanisms, including Virtual Private Networks (VPNs), facilitating secure data exchange and coordinated operations between system components.

4.2 OpenStack 

OpenStack is an open-source cloud computing platform designed to build and manage private and public cloud infrastructures. It provides a set of services that enable unified management of compute, storage, and network resources through standard Application Programming Interfaces (APIs), thereby simplifying the deployment and operation of cloud infrastructure.

**Core OpenStack components include:** 

* 
**Nova (Compute):** Manages the lifecycle of virtual machines and compute resources.


* 
**Neutron (Networking):** Provides virtual network services, including routing, firewalls, and connectivity between virtual machines.


* 
**Keystone (Identity):** Handles authentication, authorization, and identity management for users and services.


* 
**Glance (Image):** Manages virtual machine images used during deployment.


* 
**Cinder (Block Storage):** Provides block storage services for virtual machines.



4.3 Amazon Web Services (AWS) 

Amazon Web Services (AWS) is a leading public cloud service provider, offering a comprehensive ecosystem of cloud services with global infrastructure. AWS operates on a pay-as-you-go pricing model.

**Core AWS services used in this hybrid environment:** 

* 
**Amazon EC2 (Elastic Compute Cloud):** Provides scalable virtual compute instances.


* 
**Amazon VPC (Virtual Private Cloud):** Enables the creation and management of isolated virtual networks within the AWS infrastructure.


* 
**Amazon S3 (Simple Storage Service):** Provides highly durable and scalable object storage.



4.4 ELK Stack 

The ELK Stack is a widely used open-source platform for collecting, processing, storing, and analyzing log data, playing a crucial role in SIEM systems. It consists of three core components:

* 
**Logstash:** Responsible for collecting, processing, and normalizing log data from various sources. It provides mechanisms for filtering, transforming, and enriching data to standardize raw logs into a unified format.


* 
**Elasticsearch:** A distributed search and analytics engine used to store and index large volumes of log data. It provides APIs for rapid search and analysis with low latency.


* 
**Kibana:** The user interface component that provides data visualization and analysis capabilities. Users can query raw data, build monitoring dashboards, define anomaly detection rules, and access security alerts.



4.5 Nginx, MySQL, PHP 

* 
**Nginx:** A high-performance open-source web server and reverse proxy designed to handle large numbers of concurrent connections.


* 
**MySQL:** A relational database management system used for data storage, ensuring data consistency and efficient access.


* 
**PHP:** A server-side programming language used to develop dynamic web applications, often deployed with Nginx to handle business logic.



---

5. Objectives 

1. Deploy a hybrid cloud system using OpenStack and AWS, specifically deploying a web application on the AWS public cloud and a database residing within the OpenStack internal cloud.


2. Process raw data using Logstash.


3. Establish an incident logging and management system on the Kibana interface.


4. Deploy a virtual lab environment to simulate attacks and illustrate the key features of the ELK Stack.



---

6. Research Methodology 

The operational flow consists of two main streams:

1. 
**Application Stream:** The user connects to the website via API to the web server located on an AWS EC2 instance. The web server retrieves information from the database server (an OpenStack instance) via a VPN connection between the two cloud platforms.


2. 
**SIEM Stream:** Filebeat is installed on both the web server and database server to collect application logs. Filebeat pushes logs to Logstash, which normalizes them and pushes them to Elasticsearch. Kibana retrieves analyzed information from Elasticsearch to display and alert on the dashboard.



6.1 Building the Hybrid Cloud Environment 

6.1.1 OpenStack Configuration 

The internal cloud side consists of three networks (Figure 6.3):

* 
**public1:** Allows administrators to connect to VMs and VMs to connect to the internet.


* 
**dmz-net:** Used for exchanging information between the two cloud platforms.


* 
**private-net:** Where services and sensitive information are stored.



**Network Creation Commands:** 

```bash
# Create DMZ Network and Subnet
openstack network create dmz-net
openstack subnet create dmz-subnet --network dmz-net --subnet-range 192.168.10.0/24 --gateway 192.168.10.1 --allocation-pool start=192.168.10.2,end=192.168.10.253 --dns-nameserver 8.8.8.8

# Create Private Network and Subnet
openstack network create private-net
openstack subnet create private-subnet --network private-net --subnet-range 192.168.20.0/24 --dns-nameserver 8.8.8.8

```

**Instance Setup:**
Create flavors for the VPN instance (more RAM) and Service instance:

```bash
openstack flavor create m1.small --ram 2048 --disk 10 --vcpus 1
openstack flavor create backend.small --ram 1024 --disk 10 --vcpus 1

```

**Security Group (R2R - Router to Router):** 

* **Ingress:** Allow SSH, ICMP, TCP/UDP from the private-net subnet, and UDP port 51820 (WireGuard).
* **Egress:** Allow traffic to private-net on port 22 and general outbound.

**Security Group (Database):** 

* **Ingress:** Allow SSH and MySQL (port 3306) from the private-net range.
* **Egress:** Restrict outbound traffic to the R2R VPN interface IP (192.168.20.250).

**Creating Instances:** 

* **R2R Instance:** Uses `m1.small`, attached to `dmz-net`, Security Group `R2R-SG`.
* **MySQL Instance:** Uses `backend.small`, attached to `private-net`, Security Group `db-sg`.

**WireGuard Setup on OpenStack R2R:** 

1. Install WireGuard: `sudo apt install wireguard -y`.
2. Generate Keys: `wg genkey | tee privatekey | wg pubkey > publickey`.
3. Configure `/etc/wireguard/wg0.conf`:
* Define local address (`10.10.0.2/24`).
* Set Listen Port (`51820`).
* Add Peer (AWS) with its Public Key and Endpoint (`13.214.243.204:51820`).
* AllowedIPs: `10.10.0.1/32` (AWS VPN IP) and `10.0.0.0/20` (AWS VPC range).



**Routing/NAT on OpenStack R2R:** 
Configure `iptables` to SNAT traffic destined for the database so it appears to come from the R2R instance (ensuring the DB responds back to the router).

```bash
sudo iptables -t nat -A POSTROUTING -s 10.10.0.0/24 -d 192.168.20.0/24 -o ens7 -j SNAT --to-source 192.168.20.250

```

**Database Configuration:** 

* On `mysql-server1`, set default route via R2R: `sudo ip route add default via 192.168.20.250`.
* Install MySQL and configure `bind-address = 0.0.0.0`.
* Create database `website_db`, table `users` and `products`, and a user `awsuser` allowing connection from the OpenStack VPN IP .



6.1.2 AWS Configuration 

Create a VPC structure mirroring OpenStack with public and private subnets.

**VPC Configuration Summary (Table 6.1):** 

* **CIDR:** 10.0.0.0/16
* **Public Subnet:** 10.0.0.0/20
* **Private Subnet:** 10.0.128.0/20

**Instances:**

* 
**R2R Instance (AWS):** Public Subnet, Ubuntu 24.04, Security Group allowing UDP 51820 (VPN), TCP 5044 (Logstash), TCP 3306 (MySQL routing), and SSH.


* 
**Website Instance:** Private Subnet, Security Group allowing HTTP/HTTPS/SSH from the VPC range.



**WireGuard Setup on AWS R2R:** 

* Config in `/etc/wireguard/wg0.conf`.
* Address: `10.10.0.1/24`.
* Peer (OpenStack) Public Key.
* AllowedIPs: `10.10.0.2/32`, `192.168.10.0/24`, `192.168.20.0/24`.
* **PostUp/PostDown:** Scripts to enable masquerading (NAT) so traffic from the web server traversing the VPN is correctly routed.

**Web Server Setup:** 

* Install Nginx and PHP-FPM.
* Create a PHP script to connect to the OpenStack database (`192.168.20.220`) using the `awsuser`.

---

6.2 Building the SIEM 

6.2.1 Elasticsearch and Kibana (OpenStack Side) 

Due to hardware limits, these are installed on the machine running OpenStack.

* Install using `apt` with Elastic GPG keys .


* Reset passwords for `elastic` and `kibana_system` users.


* **Configuration (`elasticsearch.yml`):** Set `network.host: 0.0.0.0` and `discovery.type: single-node`. Limit JVM heap to 1GB .


* 
**Configuration (`kibana.yml`):** Set `server.host: "0.0.0.0"` and configure elasticsearch credentials .



6.2.2 Logstash (OpenStack R2R) 

Installed on the R2R instance to forward logs to Elasticsearch.

* 
**Config (`01-beats-routing.conf`):** Listens on port 5044. It uses conditionals based on the `[service]` field to route logs to different indices (`web-logs-*` or `db-logs-*`) .



#### 6.2.3 Filebeat (Log Collection)

* 
**On OpenStack Database:** Configured to harvest `/var/log/mysql/*.log`, tag with `service: db`, and output to Logstash (`192.168.20.250:5044`) .


* **On AWS Web Server:**
* Configure PHP-FPM and Nginx to log errors and access details (including body bytes and user agent) .


* Configure Filebeat to harvest Nginx access/error logs and PHP-FPM logs. Tag with `service: web` and specific tags like `nginx`, `access`, `error` .





---

7. Scenarios and Detection Rules 

Scenario 1: Broken Access Control 

* **Attack:** Accessing the admin page without login via URL manipulation (`/?admin=true`).
* 
**Detection Rule:** Elasticsearch query matching the phrase `"message": "/?admin=true"`.


* 
**Result:** Alert triggered on Kibana dashboard.



Scenario 2: SQL Injection 

* **Attack:** Accessing admin via SQL injection. Attackers often test for syntax errors first.
* 
**Detection Rule:** Detects SQL error messages in the logs, such as `"You have an error in your SQL syntax"` or `"near ..."`.


* 
**Result:** Alert triggered.



Scenario 3: XSS (Cross-Site Scripting) 

* **Attack:** Executing scripts via URL.
* 
**Detection Rule:** Query strings containing `<script>`, `%3Cscript%3E`, or `onerror=`.


* 
**Result:** Alert triggered.



Scenario 4: OS Command Injection 

* **Attack:** Injecting OS commands into an interface (e.g., a ping utility) using separators.
* 
**Detection Rule:** Detects characters like `;`, `&&`, `|` combined with command execution indicators in the message.


* 
**Result:** Alert triggered.



Scenario 5: Local File Inclusion (LFI) 

* **Attack:** Traversing directories to access sensitive files.
* 
**Detection Rule:** Detects patterns like `../` or `..\\`, or attempts to access `/etc/passwd`, `/proc/self/environ`, or `boot.ini`.


* 
**Result:** Alert triggered.



---

8. Conclusion 

The project successfully created a hybrid cloud system. The SIEM operated correctly as intended, effectively detecting simulated attacks.
