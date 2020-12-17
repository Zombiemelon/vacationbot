pipeline {
    options { timeout(time: 25, unit: 'MINUTES') }
    agent any
    environment {
        CONTAINER_NAME='vacation_bot'
        ECR_ADDRESS=credentials('aws_sd_ecr_address')
        CONTAINER_NAME_FRONT='vacation_bot_front'
        CONTAINER_NAME_BACK='vacation_bot_back'
        HOST_BACK_PORT=8443
        CONTAINER_BACK_PORT=443
        HOST_FRONT_PORT=3005
        CONTAINER_FRONT_PORT=443
        AWS_PROFILE='aws_sd'
        CONFIG='aws_sd'
    }
    stages {
//         stage ('Build Back') {
//             steps {
//                 sh "docker build -t $CONTAINER_NAME:back -f ./docker/Dockerfile.staging.backend . "
//             }
//         }
//         stage ('Test') {
//             steps {
//                 // Create network where I will connect all containers
//                 sh "docker network create test"
//                 script {
//                     //withRun command starts the container and doesn't stop it until all inside is executed.
//                     //Commands inside are executed on HOST machine
//                     docker.image("mysql/mysql-server:5.7").withRun('-p 3306:3306 -v $(pwd)/db_volume:/var/lib/mysql --name=db -e MYSQL_DATABASE=database -e MYSQL_USER=user -e MYSQL_PASSWORD=devpass -itd --network=test') {
//                             docker.image("$CONTAINER_NAME:back").inside("-itd --network=test") {
//                                 sh '/home/backend/migration.sh'
//                                 sh "cd /home/backend; php vendor/bin/codecept run acceptance"
//                             }
//                     }
//                 }
//             }
//         }
        stage ('Build Production Back') {
            // Setting environment variables
            environment {
                BOT_API=credentials("f7621b77-836e-4699-9442-b5a2456eb555")
                FACEBOOK_BOT_API_TOKEN=credentials('facebook_bot_api_token')
                DB_HOST=credentials('vacation_bot_db_host')
                DB_DATABASE=credentials('vacation_bot_db_database')
                DB_USERNAME=credentials('vacation_bot_db_username')
                DB_PASSWORD=credentials('vacation_bot_db_password')
                UNSPLASH_APP_ID=credentials('vacation_bot_unsplash_app_id')
                UNSPLASH_APP_SECRET=credentials('vacation_bot_unsplash_app_secret')
            }
            steps {
                script {
                    sh 'env > env.txt'
                    sh 'touch ./backend/.env.build'
                    readFile('env.txt').split("\r?\n").each {
                            sh "sed \"s~{${it.split("=")[0]}}~${it.split("=")[1]}~\" ./backend/.env.staging > ./backend/.env.build && mv ./backend/.env.build ./backend/.env.staging"
                    }
                    sh "chmod 777 ./backend/.env.staging && mv ./backend/.env.staging ./backend/.env"
                    sh 'cat ./backend/.env'
                    if (env.GIT_BRANCH == 'origin/master') {
                        sh "docker build --build-arg arg=.env -t $CONTAINER_NAME:back -f ./docker/Dockerfile.staging.backend . "
                    }
                }
            }
        }
        stage ('Push Image Back') {
            steps {
                script {
                    if (env.GIT_BRANCH == 'origin/master') {
                        sh "\$(/root/.local/bin/aws ecr get-login --no-include-email --region eu-central-1 --profile $AWS_PROFILE)"
                        sh "docker tag $CONTAINER_NAME:back $ECR_ADDRESS:back"
                        sh "docker push $ECR_ADDRESS:back"
                        sh "echo \"Delete image\""
                        sh "docker image rm -f ${CONTAINER_NAME}:back && docker image prune -f"
                    }
                }
            }
        }
        stage('Deploy') {
            steps {
                script {
                    if (env.GIT_BRANCH == 'origin/master') {
                        sshPublisher(publishers: [sshPublisherDesc(configName: env.CONFIG, transfers: [sshTransfer(cleanRemote: false, excludes: '', execCommand: "\$(aws ecr get-login --no-include-email --region eu-central-1) && docker pull ${ECR_ADDRESS}:back; docker rm -f ${CONTAINER_NAME_BACK} ; docker run --name ${CONTAINER_NAME_BACK} -d -p ${HOST_BACK_PORT}:${CONTAINER_BACK_PORT} ${ECR_ADDRESS}:back", execTimeout: 120000, flatten: false, makeEmptyDirs: false, noDefaultExcludes: false, patternSeparator: '[, ]+', remoteDirectory: '', remoteDirectorySDF: false, removePrefix: '', sourceFiles: '')], usePromotionTimestamp: false, useWorkspaceInPromotion: false, verbose: false)])
                    }
                }
            }
        }
     }
    post {
        always {
            cleanWs()
            sh 'docker system prune -f'
        }
     }
}