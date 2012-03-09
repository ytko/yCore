<?php defined ('_YEXEC')  or  die(_CEXEC);

$this->getPage('_style', $_);
$this->getPage('_menu', $_);

$this->body.= "<form action='' method='post'>
    <table>
        <tr>
            <td>Логин:</td>
            <td><input type='text' name='login' /></td>
        </tr>
        <tr>
            <td>Пароль:</td>
            <td><input type='password' name='password' /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type='submit' value='Войти' /></td>
        </tr>
    </table>
</form>";

?>