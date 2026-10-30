pipeline {
    agent any

    triggers { githubPush() }

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
                sh 'for i in $(seq 1 30); do curl -sf http://localhost:8082/api/health_db.php && exit 0; sleep 5; done; exit 1'
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
                recipientProviders: [developers(), requestor()]
            )
        }
    }
}
