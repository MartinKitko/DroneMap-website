<?php

/** @var Array $data */

if (isset($_SESSION['errorOccurred'])) {
    $errorOccurred = $_SESSION['errorOccurred'];
    $errorMessage = $_SESSION['errorMessage'];
    unset($_SESSION['errorOccurred']);
    unset($_SESSION['errorMessage']);
} else {
    $errorOccurred = 'false';
    $errorMessage = "";
}

function getParam($param): string|null
{
    if (isset($_POST[$param])) {
        return htmlspecialchars(trim($_POST[$param]), ENT_QUOTES);
    }
    return null;
}

?>
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signup my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Registrácia</h5>
                    <form class="form-signup" action="?c=auth&a=store" method="post" >
                        <div class="form-label-group mb-3">
                            <label for="username">Používateľské meno:</label>
                            <input name="username" maxlength="160" type="text" id="username" class="form-control"
                                   placeholder="username" required autofocus value="<?= getParam('username') ?>">
                            <div id="username-error" class="form-error"></div>
                        </div>
                        <div class="form-label-group mb-3">
                            <label for="email">Email:</label>
                            <input name="email" maxlength="60" type="email" id="email" class="form-control"
                                   placeholder="example@email.com" required value="<?= getParam('email') ?>">
                            <div id="email-error" class="form-error"></div>
                        </div>
                        <div class="form-label-group mb-3">
                            <label for="password">Heslo:</label>
                            <input name="password" maxlength="60" type="password" id="password" class="form-control"
                                   placeholder="password" required value="<?= getParam('password') ?>">
                            <div id="password-error" class="form-error"></div>
                        </div>
                        <div class="form-label-group mb-3">
                            <label for="password2">Heslo znova:</label>
                            <input name="password2" maxlength="60" type="password" id="password2" class="form-control"
                                   placeholder="password" required value="<?= getParam('password2') ?>">
                            <div id="passwords-error" class="form-error"></div>
                        </div>
                        <div class="text-center mb-3">
                            <button class="btn btn-primary" type="submit" id="submit" name="submit">Registrovať</button>
                        </div>
                    </form>
                    <div id="error-message" class="text-danger d-none"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    checkForErrors(<?= $errorOccurred ?>, '<?= $errorMessage ?>');
    setupRegisterListeners();
</script>