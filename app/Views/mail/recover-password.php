<div style="margin:0;padding:0" bgcolor="#FFFFFF">
    <table width="100%" height="100%" style="min-width:348px" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr height="32" style="height:32px">
                <td></td>
            </tr>
            <tr align="center">
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" style="padding-bottom:20px;max-width:516px;min-width:220px">
                        <tbody>
                            <tr>
                                <td width="8" style="width:8px"></td>
                                <td>
                                    <div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:40px 20px" align="center">
                                        <img src="<?= base_url("assets/img/logo.png"); ?>" width="191" height="41" aria-hidden="true" style="margin-bottom:16px" alt="Logo">
                                        <div style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:24px;text-align:center;word-break:break-word">
                                            <div style="font-size:20px">Recuperação de Senha</div>
                                            <?php if (!empty($email)) : ?>
                                                <table align="center" style="margin-top:8px">
                                                    <tbody>
                                                        <tr style="line-height:normal">
                                                            <td>
                                                                <a style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.87);font-size:14px;line-height:20px">
                                                                    <?= (empty($nome) ? $email : "{$nome} ($email)"); ?>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding-top:20px;text-align:center">
                                            Para realizar a troca da sua senha, clique no botão abaixo para redirecionarmos você. <br>
                                            Caso não tenha sido você que realizou esta solicitação, favor ignorar este e-mail.
                                            <div style="padding-top:32px;text-align:center">
                                                <a href="<?= $link; ?>" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#2ea6ff;border-radius:5px;min-width:90px" target="_blank">Alterar Senha</a>
                                            </div>
                                        </div>
                                        <div style="padding-top:20px;font-size:12px;line-height:16px;color:#5f6368;letter-spacing:0.3px;text-align:center">Desenvolvido por<br>
                                            <a href="https://iluminareweb.com.br" target="_blank" style="color:rgba(0,0,0,0.87);text-decoration:inherit">Iluminare Web</a>
                                        </div>
                                    </div>
                                    <div style="text-align:left">
                                        <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">
                                            <div>Para sua segurança, não encaminhe este e-mail para ninguém.</div>
                                            <a style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">
                                                © <?= date("Y"); ?> Iluminare Web
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td width="8" style="width:8px"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr height="32" style="height:32px">
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
