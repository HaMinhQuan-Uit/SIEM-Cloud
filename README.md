
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
**Nova (Compute):** Manages the lifecycle of virtual machines and compute resources.
**Neutron (Networking):** Provides virtual network services, including routing, firewalls, and connectivity between virtual machines. 
**Keystone (Identity):** Handles authentication, authorization, and identity management for users and services.
**Glance (Image):** Manages virtual machine images used during deployment. 
**Cinder (Block Storage):** Provides block storage services for virtual machines.
4.3 Amazon Web Services (AWS) 
Amazon Web Services (AWS) is a leading public cloud service provider, offering a comprehensive ecosystem of cloud services with global infrastructure. AWS operates on a pay-as-you-go pricing model.
**Core AWS services used in this hybrid environment:** 
**Amazon EC2 (Elastic Compute Cloud):** Provides scalable virtual compute instances.
**Amazon VPC (Virtual Private Cloud):** Enables the creation and management of isolated virtual networks within the AWS infrastructure.
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

8. Conclusion 

The project successfully created a hybrid cloud system. The SIEM operated correctly as intended, effectively detecting simulated attacks.
