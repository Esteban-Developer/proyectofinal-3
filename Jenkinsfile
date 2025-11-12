pipeline {
    agent any

    environment {
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-cred')   
        APP_IMAGE = "esteban889/proyectofinal-3"
        DB_IMAGE = "esteban889/threaderz-mysql"
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/Esteban-Developer/proyectofinal-3.git'
            }
        }

        stage('Detect Changes') {
            steps {
                script {
                    def currentCommit = sh(script: "git rev-parse HEAD", returnStdout: true).trim()
                    def commitFile = "${env.WORKSPACE}/.last_commit"

                    if (fileExists(commitFile)) {
                        def lastCommit = readFile(commitFile).trim()
                        if (currentCommit == lastCommit) {
                            echo "No hay cambios nuevos desde el último despliegue (${lastCommit})."
                            currentBuild.result = 'SUCCESS'
                            currentBuild.displayName = "Sin cambios"
                            error("No hay cambios nuevos — omitiendo despliegue.")
                        } else {
                            echo "Cambios detectados. Último commit anterior: ${lastCommit}"
                        }
                    } else {
                        echo "Primer despliegue: no existe registro previo de commit."
                    }

                    writeFile file: commitFile, text: currentCommit
                }
            }
        }

        stage('Generate Tag') {
            steps {
                script {
                    def GIT_COMMIT = sh(script: "git rev-parse --short HEAD", returnStdout: true).trim()
                    def DATE_TAG = sh(script: "date +%Y%m%d-%H%M%S", returnStdout: true).trim()
                    def VERSION_TAG = "${DATE_TAG}-${GIT_COMMIT}"
                    env.VERSION_TAG = VERSION_TAG
                    echo "Versión generada: ${VERSION_TAG}"
                }
            }
        }

        stage('Build App Image') {
            steps {
                sh '''
                    echo "=== Construyendo imagen de la App ==="
                    docker build -t $APP_IMAGE:$VERSION_TAG .
                    docker tag $APP_IMAGE:$VERSION_TAG $APP_IMAGE:latest
                '''
            }
        }

        stage('Build MySQL Image') {
            steps {
                sh '''
                    echo "=== Construyendo imagen de MySQL con datos ==="
                    docker build -f Dockerfile.mysql -t $DB_IMAGE:$VERSION_TAG .
                    docker tag $DB_IMAGE:$VERSION_TAG $DB_IMAGE:latest
                '''
            }
        }

        stage('Login to DockerHub') {
            steps {
                sh 'echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin'
            }
        }

        stage('Push to DockerHub') {
            steps {
                sh '''
                    echo "=== Subiendo imágenes a DockerHub ==="
                    
                    # App
                    docker push $APP_IMAGE:$VERSION_TAG
                    docker push $APP_IMAGE:latest
                    
                    # MySQL
                    docker push $DB_IMAGE:$VERSION_TAG
                    docker push $DB_IMAGE:latest
                '''
            }
        }

        stage('Deploy Local') {
            steps {
                script {
                    echo "=== Desplegando aplicación localmente ==="
                    sh '''
                        docker-compose down || true
                        sleep 5
                        docker-compose pull
                        docker-compose up -d
                        
                        echo "Esperando a que MySQL esté listo..."
                        sleep 20
                        
                        docker-compose ps
                    '''
                }
            }
        }

        stage('Health Check') {
            steps {
                script {
                    echo "=== Verificando aplicación ==="
                    sh '''
                        timeout 30 bash -c 'until curl -f http://localhost:8080 > /dev/null 2>&1; do 
                            echo "Esperando..."
                            sleep 2
                        done'
                        
                        echo "✅ App funcionando en http://localhost:8080"
                    '''
                }
            }
        }
    }

    post {
        always {
            sh 'docker logout'
            sh 'docker system prune -f || true'
        }
        success {
            echo "✅ =============================================="
            echo "✅ Pipeline completado con éxito"
            echo "=============================================="
            echo ""
            echo "📦 Imágenes subidas a DockerHub:"
            echo "   🐘 App PHP: $APP_IMAGE:latest"
            echo "   🗄️  MySQL: $DB_IMAGE:latest"
            echo ""
            echo "🚀 Para desplegar en cualquier máquina:"
            echo "   curl -sSL https://raw.githubusercontent.com/Esteban-Developer/proyectofinal-3/main/quick-deploy.sh | bash"
            echo ""
            echo "=============================================="
        }
        failure {
            echo "❌ Pipeline falló"
            sh 'docker-compose logs --tail=50 || true'
        }
    }
}