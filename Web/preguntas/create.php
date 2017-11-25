<?php
// Include config file
require_once 'config.php';

$sql = "SELECT * FROM tematicas";
$result = $mysqli->query($sql);

if(empty($result)) {
	echo "Vacio";
}
 
// Define variables and initialize with empty values
$name = $tematica = "";
$name_err = $tematica_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
	$combo_tematica = trim($_POST["tematica"]);
    if(empty($input_name)){
        $name_err = "Por favor ingrese una descripción.";
    } else if(empty($combo_tematica)){
        $tematica_err = "Por favor seleccione una temática.";
    } else {
        $name = $input_name;
		$tematica = $combo_tematica;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($tematica_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO preguntas (descripcion, id_tematica) VALUES (?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_name, $param_tematica);
            
            // Set parameters
            $param_name = $name;
			$param_tematica = $tematica;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Ocurrió un error. Intente nuevamente más tarde.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Crear registro</h2>
                    </div>
                    <p>Por favor complete la descripción y seleccione la temática correspondiente para agregar una nueva pregunta a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Descripción</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($tematica_err)) ? 'has-error' : ''; ?>">
                            <label>Temática</label>
							<select name="tematica" id="tematica">
							<?php while($ri = mysqli_fetch_array($result)) { ?>
							<option value="<?php echo $ri['id_tematicas'] ?>"><?php echo $ri['descripcion'] ?></option>
							<?php } ?>
							</select>
                            <span class="help-block"><?php echo $tematica_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Guardar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>