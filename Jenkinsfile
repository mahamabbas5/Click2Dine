pipeline {
    agent any

    triggers { githubPush() }          // fired by the GitHub webhook

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/mahamabbas5/Click2Dine.git'
            }
        }

        stage('Lint PHP (Docker Pipeline)') {
            steps {
                script {
                    docker.image('php:8.2-cli').inside {
                        sh 'find api -name "*.php" -print0 | xargs -0 -n1 php -l'
                    }
                }
            }
        }

        stage('Build & Deploy containers') {
            steps {
                sh 'docker compose -f docker-compose.jenkins.yml down || true'
                sh 'docker compose -f docker-compose.jenkins.yml up -d --force-recreate'
            }
        }

        stage('Verify') {
            steps {
                sh 'sleep 60'
                sh 'curl -sf http://localhost:8082/api/health_db.php'
            }
        }
    }

    post {
        always {
            emailext(
                subject: "Click2Dine build #${env.BUILD_NUMBER}: ${currentBuild.currentResult}",
                body: """Job: ${env.JOB_NAME}
Build: #${env.BUILD_NUMBER}
Result: ${currentBuild.currentResult}
Details: ${env.BUILD_URL}""",
                recipientProviders: [developers(), requestor()]   // the person who pushed
            )
        }
    }
}
