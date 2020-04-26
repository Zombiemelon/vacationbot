FROM docker.elastic.co/logstash/logstash:7.6.2

COPY ./logstash.yml /usr/share/logstash/config/logstash.yml
COPY ./pipeline.conf /usr/share/logstash/pipeline/logstash.conf

EXPOSE 5044