---
extends: _layouts.post
section: content
title: Laravel Vapor cloud dashboard using only Cloudwatch
author: George Boot
date: 2021-09-17
description: In a recent blogpost, Michael Dyrynda showed his Vapor dashboard built using Grafana. I re-created the dash using Cloudwatch.
categories: [tech]
cover_image: /assets/img/cloud-dashboard.png
featured: true
---

In a [recent blogpost](https://dyrynda.com.au/blog/monitoring-laravel-vapor-with-grafana-cloud), Michael Dyrynda showed a dashboard he has build for monitoring [thenping.me](https://thenping.me/). As Laravel Vapor only includes a very basic dashboard, he created a custom one. He used Grafana Cloud to build the dashboard.

When I read the article, I wondered why he used Grafana as AWS itself also offers a similar service. In fact, the data that is used on the dashboard, comes from AWS Cloudwatch, and Cloudwatch itself has very powerful dashboard functionalities built in. Unlike Grafana, it can't (easily at least) read data from a wide range of external sources, but since that is not really the case for this project, I don't think it matters.

Because I believe this dash could just as well be build using native Cloudwatch, I challenged myself to do so. When I shared this idea in the Serverless Laravel Slack, people seemed very interested and asked me if I was willing to share the dashboard. I am willing to do just that.

## Lambda Insights
By default, Lambda does not export memory statistics to Cloudwatch. The only way to get information about memory and cold starts, is by parsing the raw logs. This can be done using Cloudwatch Logs Insights, but these values can't be nicely shown on your dashboard.

A while ago, AWS announced Lambda Insights. This is basically an opt-in extension to your functions, that will export statistics like actual memory usage, network throughput and cpu cycles to Cloudwatch.

Enabling this extension can simply be done by adding a layer in your `vapor.yml`. Look up the layer for your region [on this page](https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/Lambda-Insights-extension-versions.html).

Example `vapor.yml`:

```yaml
id: 123456
name: my-awesome-app

environments:
  production:
    runtime: al2
    layers:
      - vapor:php-8.0:al2
      - arn:aws:lambda:eu-west-1:580247275435:layer:LambdaInsightsExtension:14
```

Unfortunately, I haven't found a way to enable Lambda Insights for docker-based deployments. No worries, the rest of the dash will still work.

## Copy, Search, Replace, Paste, Enjoy
All developers want in the end, is a simple copy/paste solution. I am pleased to inform you that you are about to get that! In Cloudwatch, it is possible to export and import complete dashboards as json. Practically this means, that once you copy the below json, you will have to search and replace the following values:

- Replace `PROJECT_NAME` with the name of your Vapor project, eg. `awesome-site`
- Replace `PROJECT_ENV` with the environment of your deployment, eg. `production`. If you are using Docker deployments, add `-d` to the end of your env (`production-d`, `staging-d`, etc.)
- Replace `PROJECT_REGION` with the AWS region your project lives in, eg. `eu-west-1`
- Replace `DATABASE_NAME` with the name of your database as configured in Vapor, eg. `my-app-db`
- Replace `API_GATEWAY_ID` with the ID of your projects API gateway. You can find this using the AWS Console. Example: `yuttpf0t41`

Note that the dashboard is made for API Gateway users. If you use a Application Elastic Load Balancer, an example is provided below the main json.

Once you have prepared the json in your editor of choice, head to the AWS Console to import the dashboard.

- After logging in, go to **Cloudwatch**, and in the menu of the left, select **Dashboards**
- Click **Create dashboard**, enter a name and once created, dismiss the popup to add your first graph as you won't need it
- In the top bar, select **Actions** and click **View/edit source**.
- Paste your prepared json, and hit **Update**
- Hit **Save Dashboard**
- Congrats, you now have an awesome dashboard!

## Pro tip: view dashboard without loggin in
If you don't want to log in every time you want to check the dashboard (or want to permanently show it on a wall-mounted tv etc.), you can share your dashboard using a public url. Everyone with that url, will be able to view the dashboard and underlying metrics.

You can obtain such a public url, by clicking **Actions** in the top menu, and selecting **Share dashboard**. Pick **Share your dashboard publicly**, read the warning message and confirm.

Wanna express your gratitude with a donation? [Become a (one-time) sponsor on GitHub Sponsor](https://github.com/sponsors/georgeboot).

Have any questions? At the [bottom of the page](#comments), you can comment on this post.

The dashboard json:
```json
{
    "widgets": [
        {
            "height": 4,
            "width": 8,
            "y": 0,
            "x": 8,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/Lambda", "Invocations", "FunctionName", "vapor-PROJECT_NAME-PROJECT_ENV", { "color": "#2ca02c" } ],
                    [ ".", "Errors", ".", ".", { "color": "#ff7f0e" } ],
                    [ ".", "Duration", ".", ".", { "yAxis": "right", "color": "#1f77b4", "stat": "Average" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Sum",
                "period": 300,
                "title": "Lambda Invocations - HTTP",
                "yAxis": {
                    "right": {
                        "showUnits": true
                    },
                    "left": {
                        "showUnits": true
                    }
                },
                "liveData": false,
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 12,
            "x": 0,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/RDS", "DatabaseConnections", "DBInstanceIdentifier", "DATABASE_NAME", { "color": "#2ca02c" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "annotations": {
                    "horizontal": [
                        {
                            "color": "#ff7f0e",
                            "label": "Warning",
                            "value": 60,
                            "fill": "above"
                        },
                        {
                            "color": "#d62728",
                            "label": "Max",
                            "value": 85,
                            "fill": "above"
                        }
                    ]
                },
                "yAxis": {
                    "left": {
                        "min": 0,
                        "showUnits": false
                    }
                },
                "title": "RDS Connections",
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 12,
            "x": 4,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/RDS", "DiskQueueDepth", "DBInstanceIdentifier", "DATABASE_NAME" ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "yAxis": {
                    "left": {
                        "showUnits": false,
                        "min": 0
                    }
                },
                "title": "RDS Queue Depth",
                "legend": {
                    "position": "hidden"
                },
                "annotations": {
                    "horizontal": [
                        {
                            "color": "#ff7f0e",
                            "label": "Warning",
                            "value": 2,
                            "fill": "above"
                        }
                    ]
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 12,
            "x": 8,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/RDS", "WriteIOPS", "DBInstanceIdentifier", "DATABASE_NAME", { "color": "#2ca02c" } ],
                    [ ".", "ReadIOPS", ".", ".", { "color": "#1f77b4" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "title": "RDS IOPs",
                "legend": {
                    "position": "hidden"
                },
                "yAxis": {
                    "left": {
                        "min": 0
                    }
                }
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 0,
            "x": 16,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/Lambda", "ConcurrentExecutions", "FunctionName", "vapor-PROJECT_NAME-PROJECT_ENV" ],
                    [ "...", "vapor-PROJECT_NAME-PROJECT_ENV-cli" ],
                    [ "...", "vapor-PROJECT_NAME-PROJECT_ENV-queue" ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Maximum",
                "period": 300,
                "title": "Lambda Concurrent Invocations",
                "yAxis": {
                    "right": {
                        "showUnits": true
                    },
                    "left": {
                        "min": 0
                    }
                },
                "liveData": false,
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 4,
            "x": 16,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/Lambda", "Throttles", "FunctionName", "vapor-PROJECT_NAME-PROJECT_ENV" ],
                    [ "...", "vapor-PROJECT_NAME-PROJECT_ENV-cli" ],
                    [ "...", "vapor-PROJECT_NAME-PROJECT_ENV-queue" ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Maximum",
                "period": 300,
                "title": "Lambda Throttles",
                "yAxis": {
                    "right": {
                        "showUnits": true
                    }
                },
                "liveData": false,
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 4,
            "x": 8,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/Lambda", "Invocations", "FunctionName", "vapor-PROJECT_NAME-PROJECT_ENV-cli", { "color": "#2ca02c" } ],
                    [ ".", "Errors", ".", ".", { "color": "#ff7f0e" } ],
                    [ ".", "Duration", ".", ".", { "yAxis": "right", "color": "#1f77b4", "stat": "Average" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Sum",
                "period": 300,
                "title": "Lambda Invocations - CLI",
                "yAxis": {
                    "right": {
                        "showUnits": true
                    },
                    "left": {
                        "showUnits": true
                    }
                },
                "liveData": false,
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 8,
            "x": 8,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/Lambda", "Invocations", "FunctionName", "vapor-PROJECT_NAME-PROJECT_ENV-queue", { "color": "#2ca02c" } ],
                    [ ".", "Errors", ".", ".", { "color": "#ff7f0e" } ],
                    [ ".", "Duration", ".", ".", { "yAxis": "right", "color": "#1f77b4", "stat": "Average" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Sum",
                "period": 300,
                "title": "Lambda Invocations - Queue",
                "yAxis": {
                    "right": {
                        "showUnits": true
                    },
                    "left": {
                        "showUnits": true
                    }
                },
                "liveData": false,
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 0,
            "x": 0,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "LambdaInsights", "total_memory", "function_name", "vapor-PROJECT_NAME-PROJECT_ENV", { "label": "Provisioned", "id": "m1" } ],
                    [ ".", "memory_utilization", ".", ".", { "label": "Avg. utilisation", "id": "m2" } ],
                    [ ".", "used_memory_max", ".", ".", { "label": "Max", "id": "m3", "stat": "Maximum" } ],
                    [ "AWS/Lambda", "Duration", "FunctionName", ".", { "label": "Avg. duration", "visible": false } ]
                ],
                "view": "singleValue",
                "title": "Lambda Memory - HTTP",
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "setPeriodToTimeRange": true
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 4,
            "x": 0,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "LambdaInsights", "total_memory", "function_name", "vapor-PROJECT_NAME-PROJECT_ENV-cli", { "label": "Provisioned", "id": "m1" } ],
                    [ ".", "memory_utilization", ".", ".", { "label": "Avg. utilisation", "id": "m2" } ],
                    [ ".", "used_memory_max", ".", ".", { "label": "Max", "id": "m3", "stat": "Maximum" } ],
                    [ "AWS/Lambda", "Duration", "FunctionName", ".", { "label": "Avg. duration", "visible": false } ]
                ],
                "view": "singleValue",
                "title": "Lambda Memory - CLI",
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "setPeriodToTimeRange": true
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 8,
            "x": 0,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "LambdaInsights", "total_memory", "function_name", "vapor-PROJECT_NAME-PROJECT_ENV-queue", { "label": "Provisioned", "id": "m1" } ],
                    [ ".", "memory_utilization", ".", ".", { "label": "Avg. utilisation", "id": "m2" } ],
                    [ ".", "used_memory_max", ".", ".", { "label": "Max", "id": "m3", "stat": "Maximum" } ]
                ],
                "view": "singleValue",
                "title": "Lambda Memory - Queue",
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "setPeriodToTimeRange": true
            }
        },
        {
            "height": 4,
            "width": 8,
            "y": 8,
            "x": 16,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/ApiGateway", "IntegrationLatency", "Stage", "PROJECT_ENV", "ApiId", "API_GATEWAY_ID", { "color": "#2ca02c" } ],
                    [ ".", "DataProcessed", ".", ".", ".", ".", { "yAxis": "right", "color": "#1f77b4" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "title": "API Gateway",
                "yAxis": {
                    "right": {
                        "showUnits": true
                    }
                },
                "liveData": false,
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 16,
            "x": 8,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/RDS", "BinLogDiskUsage", "DBInstanceIdentifier", "DATABASE_NAME", { "color": "#2ca02c", "id": "m1" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "title": "RDS Binlog Usage",
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 12,
            "x": 12,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/RDS", "SwapUsage", "DBInstanceIdentifier", "DATABASE_NAME" ],
                    [ ".", "FreeableMemory", ".", "." ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "title": "RDS Swap Usage",
                "legend": {
                    "position": "hidden"
                },
                "yAxis": {
                    "left": {
                        "min": 0
                    }
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 12,
            "x": 16,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/SQS", "NumberOfMessagesReceived", "QueueName", "PROJECT_NAME-PROJECT_ENV", { "color": "#2ca02c" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Sum",
                "period": 300,
                "title": "SQS Messages",
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 12,
            "x": 20,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/SQS", "ApproximateAgeOfOldestMessage", "QueueName", "PROJECT_NAME-PROJECT_ENV", { "stat": "Maximum" } ],
                    [ ".", "SentMessageSize", ".", ".", { "color": "#2ca02c", "yAxis": "right" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "title": "SQS Message Size and Age",
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 16,
            "x": 20,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/SQS", "NumberOfMessagesReceived", "QueueName", "PROJECT_NAME-PROJECT_ENV", { "color": "#2ca02c" } ],
                    [ ".", "NumberOfMessagesDeleted", ".", "." ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Sum",
                "period": 300,
                "title": "SQS Message Receives",
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 16,
            "x": 16,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/DynamoDB", "ConsumedWriteCapacityUnits", "TableName", "vapor_cache", { "color": "#2ca02c" } ],
                    [ ".", "ConsumedReadCapacityUnits", ".", "." ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "title": "DynamoDB  - Consumed Capacity Units",
                "legend": {
                    "position": "hidden"
                },
                "yAxis": {
                    "left": {
                        "min": 0
                    }
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 16,
            "x": 12,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/DynamoDB", "ConditionalCheckFailedRequests", "TableName", "vapor_cache", { "color": "#2ca02c" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Sum",
                "period": 300,
                "title": "DynamoDB  - Conditional Check Failures",
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 16,
            "x": 4,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/RDS", "BurstBalance", "DBInstanceIdentifier", "DATABASE_NAME", { "color": "#2ca02c" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "annotations": {
                    "horizontal": [
                        {
                            "label": "Limit",
                            "value": 25,
                            "fill": "below"
                        }
                    ]
                },
                "yAxis": {
                    "left": {
                        "min": 0,
                        "max": 100,
                        "showUnits": false
                    }
                },
                "title": "RDS Burst Balance",
                "legend": {
                    "position": "hidden"
                }
            }
        },
        {
            "height": 4,
            "width": 4,
            "y": 16,
            "x": 0,
            "type": "metric",
            "properties": {
                "metrics": [
                    [ "AWS/RDS", "CPUUtilization", "DBInstanceIdentifier", "DATABASE_NAME", { "id": "m2", "color": "#2ca02c" } ]
                ],
                "view": "timeSeries",
                "stacked": false,
                "region": "PROJECT_REGION",
                "stat": "Average",
                "period": 300,
                "annotations": {
                    "horizontal": [
                        {
                            "label": "Crirical",
                            "value": 75,
                            "fill": "above"
                        },
                        {
                            "label": "Warning",
                            "value": 50
                        }
                    ]
                },
                "yAxis": {
                    "left": {
                        "min": 0,
                        "max": 100,
                        "showUnits": false
                    }
                },
                "title": "RDS CPU",
                "legend": {
                    "position": "hidden"
                }
            }
        }
    ]
}
```

Application Elastic Load Balancer example snippet:
```json
{
    "view": "timeSeries",
    "stacked": false,
    "metrics": [
        [ "AWS/ApplicationELB", "ProcessedBytes", "LoadBalancer", "app/AELB_NAME" ],
        [ ".", "LambdaTargetProcessedBytes", ".", "." ]
    ],
    "region": "PROJECT_REGION",
    "title": "Application ELB",
    "period": 300,
    "legend": {
        "position": "hidden"
    }
}
```

Make sure to replace `AELB_NAME` with the instance name of your AELB.