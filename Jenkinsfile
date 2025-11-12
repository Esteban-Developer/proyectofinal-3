pipeline {
    agent any

    environment {
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-cred')   
        IMAGE_NAME = "esteban889/proyectofinal-3"
        COMPOSE_PROJECT_NAME = "threaderz"
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

        stage('Build Docker Image') {
            steps {
                sh '''
                    echo "=== Construyendo imagen Docker ==="
                    docker build -t $IMAGE_NAME:$VERSION_TAG .
                    docker tag $IMAGE_NAME:$VERSION_TAG $IMAGE_NAME:latest
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
                    echo "=== Subiendo imagen a DockerHub ==="
                    docker push $IMAGE_NAME:$VERSION_TAG
                    docker push $IMAGE_NAME:latest
                '''
            }
        }

        stage('Deploy Application') {
            steps {
                script {
                    echo "=== Desplegando aplicación con Docker Compose ==="
                    sh '''
                        # Detener contenedores antiguos si existen
                        docker-compose down || true
                        
                        # Esperar un momento para liberar puertos
                        sleep 5
                        
                        # Descargar última imagen
                        docker pull $IMAGE_NAME:latest
                        
                        # Levantar servicios
                        docker-compose up -d
                        
                        # Verificar que los contenedores estén corriendo
                        echo "=== Estado de contenedores ==="
                        docker-compose ps
                        
                        # Esperar a que MySQL esté listo
                        echo "Esperando a que MySQL esté listo..."
                        sleep 15
                        
                        # Verificar logs
                        echo "=== Logs de la aplicación ==="
                        docker-compose logs --tail=20 app
                    '''
                }
            }
        }

        stage('Health Check') {
            steps {
                script {
                    echo "=== Verificando salud de la aplicación ==="
                    sh '''
                        # Verificar que el puerto 8080 esté escuchando
                        timeout 30 bash -c 'until curl -f http://localhost:8080 > /dev/null 2>&1; do 
                            echo "Esperando a que la app responda..."
                            sleep 2
                        done'
                        
                        echo "✅ Aplicación respondiendo correctamente en http://localhost:8080"
                        
                        # Verificar conexión a MySQL
                        docker exec threaderz_db mysql -uroot -p12345 -e "SELECT 'MySQL OK' as status;" threaderz_store
                        
                        echo "✅ Base de datos MySQL funcionando correctamente"
                    '''
                }
            }
        }
    }

    post {
        always {
            echo "=== Limpieza final ==="
            sh 'docker logout'
            sh 'docker system prune -f || true'
        }
        success {
            echo "✅ =============================================="
            echo " Pipeline completado con éxito"
            echo "✅ =============================================="
            echo ""
            echo " Imágenes subidas:"
            echo "   -> $IMAGE_NAME:latest"
            echo "   -> $IMAGE_NAME:$VERSION_TAG"
            echo ""
            echo "🚀 Aplicación desplegada y funcionando"
            echo "   -> http://localhost:8080"
            echo ""
            echo " Base de datos MySQL lista"
            echo "   -> Puerto: 3306"
            echo "=============================================="
        }
        failure {
            echo "❌ Pipeline falló - Revisando logs..."
            sh '''
                echo "=== Logs de Docker Compose ==="
                docker-compose logs --tail=50 || true
                
                echo "=== Estado de contenedores ==="
                docker ps -a || true
            '''
        }
    }
}