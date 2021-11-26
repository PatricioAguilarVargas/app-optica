<?php

namespace app\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules() {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload() {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }

    public function downloadFile($dir, $file, $extensions = []) {
        //Si el directorio existe
        if (is_dir($dir)) {
            //Ruta absoluta del archivo
            $path = $dir . $file;

            //Si el archivo existe
            if (is_file($path)) {
                //Obtener información del archivo
                $file_info = pathinfo($path);
                //Obtener la extensión del archivo
                $extension = $file_info["extension"];

                if (is_array($extensions)) {
                    //Si el argumento $extensions es un array
                    //Comprobar las extensiones permitidas
                    foreach ($extensions as $e) {
                        //Si la extension es correcta
                        if ($e === $extension) {
                            //Procedemos a descargar el archivo
                            // Definir headers
                            $size = filesize($path);
                            header("Content-Type: application/force-download");
                            header("Content-Disposition: attachment; filename=$file");
                            header("Content-Transfer-Encoding: binary");
                            header("Content-Length: " . $size);
                            // Descargar archivo
                            readfile($path);
                            //Correcto
                            return true;
                        }
                    }
                }
            }
        }
        //Ha ocurrido un error al descargar el archivo
        return false;
    }

}
