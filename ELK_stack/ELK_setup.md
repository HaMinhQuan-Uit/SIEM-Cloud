# Install Elastic search and Kibana
```bash
curl -fsSL https://artifacts.elastic.co/GPG-KEY-elasticsearch |sudo gpg --dearmor -o /usr/share/keyrings/elastic.gpg
echo "deb [signed-by=/usr/share/keyrings/elastic.gpg] https://artifacts.elastic.co/packages/7.x/apt stable main" | sudo tee -a /etc/apt/sources.list.d/elastic-7.x.list
sudo apt update
sudo apt install elasticsearch
sudo apt install kibana
```
Create new password for elastic search and Kibana
```bash
sudo /usr/share/elasticsearch/bin/elasticsearch-reset-password -u elastic
sudo /usr/share/elasticsearch/bin/elasticsearch-reset-password -u kibana_system
```
configure elasticsearch and kibana
```bash
sudo nano /etc/elasticsearch/elasticsearch.yml
sudo nano /etc/kibana/kibana.yml
```
