<?php
    require "../../vendor/autoload.php";
    use Google\Cloud\Storage\StorageClient;

    class storage{
        
        private $projectId;
        private $storage;
        private $bucketName;

        function __construct(){
            $this->projectId="pelagic-pod-279916";
            $this->storage = new StorageClient([
                'projectId' => $this->projectId
                ]);
            $this->bucketName="une_segmento-one";
 
            // Credecial para la forma manual
            //C:\\wamp64\\www\\plataforma_estudiante2.0\\includes\\credentials\\My First Project-043934006766.json
            // putenv("GOOGLE_APPLICATION_CREDENTIALS=D:\\wamp64\\www\\plataforma_estudiante2.0\\includes\\credentials\\My First Project-043934006766.json");
            // putenv("GOOGLE_APPLICATION_CREDENTIALS=D:\\wamp64\\www\\plataforma_estudiante2.0\\includes\\credentials\\My First Project-043934006766.json");
        }
        
        //Crea un nuevo segmento de almacenamiento
        public function createBucket($bucketName){
            $bucket = $this->storage->createBucket($bucketName);
            echo 'Bucket' . $bucket->name() . 'created.';
        }

        //Lista todos los segmentos de almacenamiento
        public function listBuckets(){
            $buckets = $this->storage->buckets();
            foreach($buckets as $bucket){
                echo $bucket->name() . '<br>';
            }
        }

        //Sube archivo al segmento inicializado por defecto al inicio de la clase 'une_segmento-one'
        function uploadObject($objectName, $source, $destino){
            $file = fopen($source, 'r');
            $bucket = $this->storage->bucket($this->bucketName);
            $options = [
                'name' => $destino.$objectName,
                'resumable' => true,
                'metadata' => [
                    'contentLanguage' => 'es'
                    ]
            ];

            $object = $bucket->upload($file, $options);

            // printf('Uploaded %s to gs://%s/%s' . '<br>', basename($source), $this->bucketName, $directorio_main.$objectName);
            // echo '<br>'.$source.'<br>';
        }

        //funcion en en desarrollo .....
        function exitsObject($urlfull){
            $bucket = $this->storage->bucket($this->bucketName);
            // $object = $bucket->object($objectName);
            $objectName=$bucket->downloadfile($urlfull);
            
            // printf('Downloaded gs://%s/%s to %s' . PHP_EOL, $bucketName, $objectName, basename($destination));
        }
    }
?>