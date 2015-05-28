<?php
$who_stick_it = get_option('who_stick_it');
$table_who_stick_it = json_decode($who_stick_it, true);

if (isset($_POST['sup_suc'])) {
    unset($table_who_stick_it[$_POST['sup_suc']]);
    $new_value = json_encode($table_who_stick_it);
    self::set_option('who_stick_it', $new_value);
}


if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $espace = $_POST['space'];
    $type = $_POST['type'];
    if (isset($who_stick_it)) {
        $table_who_stick_it = json_decode($who_stick_it, true);
        $sum = key($table_who_stick_it) + 1;
        $table_who_stick_it[$sum] = array($name, $espace, $type);
        $new_value = json_encode($table_who_stick_it);
        self::set_option('who_stick_it', $new_value);
    } else {
        $table_who_stick_it = array();
        $table_who_stick_it[] = array($name, $espace, $type);
        $new_value = json_encode($table_who_stick_it);
        self::set_option('who_stick_it', $new_value);
    }
}

if ((isset($_POST['id_stick'])) && (isset($_POST['space'])) && (isset($_POST['name']))) {
    $table_who_stick_it = json_decode($who_stick_it, true);
    $name = $_POST['name'];
    $espace = $_POST['space'];
    $type = $_POST['type'];
    $table_who_stick_it[$_POST['id_stick']] = array($name, $espace, $type);
    $new_value = json_encode($table_who_stick_it);
    self::set_option('who_stick_it', $new_value);
}
?>   
<div class="block_admin" style="float: left; width: 90%; margin: 0%; padding: 0%; height: 50px;">
    <h1>Who stick it</h1>
    <p>-Choissisez le type d'élément à viser : une class ou un ID.</p>
    <p>-Indiquer la distance en pixel entre votre class/ID et le haut de la page, quand l'utilisateur vas scroller le menu restera bloqué à la distance que vous aurrez indiqué.</p>

    <table>
        <tr>
        <thead>
        <th>Type</th>
        <th>Nom de votre class ou id</th>
        <th>Espace en pixel avec le haut de la page</th>
        <th colspan="2">Edition / Suppression</th>
        </tr>
        </thead>
        <tbody>
        <form method="POST">
            <tr >
                <td  >    
                    <select name="type" required>
                        <option value="class">class</option>
                        <option value="id">id</option>
                    </select>
                </td>
                <td>    
                    <input type="text" name="name" placeholder="Nom de votre class ou id" required style="width: 200px">
                </td>
                <td >    
                    <input  type="number" name="space" placeholder="Taille en pixel" value="0" >px
                </td>
                <td >     
                    <input type="submit" class="button button-primary" value="Ajouter" style="width: 100px">
                </td>
            </tr>
        </form>
        <?php
        if (isset($table_who_stick_it)) {
            foreach ($table_who_stick_it as $key => $value) {
                ?>

                <tr>
                <form method="POST">
                    <td>
                        <select name="type" required>
                            <option value="class" <?php echo ($value[2] == "class") ? 'selected' : '' ?>>class</option>
                            <option value="id" <?php echo ($value[2] == "id") ? 'selected' : '' ?>>id</option>
                        </select>
                    </td>
                    <td>  
                        <input type="text" name="name" value="<?php echo $value[0] ?>" required style="width: 200px">
                    </td>
                    <td> 
                        <input type="text" name="space" value="<?php echo $value[1] ?>">pixel
                    </td>
                    <td>    
                        <input type="submit" class="button button-primary" value="Editer" style="width: 100px">
                        <input type="hidden" name="id_stick" value="<?php echo $key ?>">
                    </td>
                </form>
                <td>   
                    <form method="POST">
                        <input type="hidden" name="sup_suc" value="<?php echo $key ?>" >
                        <input type="submit" class="button button-primary" value="Supprimer" style="background: red; border-color: brown; width: 100px;" onclick="javascript:return confirm('Etes vous sur?')">
                    </form>
                </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>