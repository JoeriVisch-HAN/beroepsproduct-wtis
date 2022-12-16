<?php
include("./components/header.html");
?>

<form action="#" method="post">
            <h1>login</h1>
            <p>Log hier in om verder te kunnen op de website!</p>
            <label>
                passagier of medewerker?
                <select>
                    <option>passagier</option>
                    <option>medewerker</option>
                </select>
            </label>
            <label>
                username:
                <input type="text" name="username" pattern="[a-zA-Z]+" required>
            </label>
            <label>
                password:
                <input type="password" name="password" minlength="5" required>
            </label>
            <label>
                inloggen:
                <input type="submit" name="inloggen" value="inloggen">
            </label>
        </form>