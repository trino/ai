Hi {{ $name }}. Thank you for registering at londonpizza.ca
Your password is {{ $password }}

@if($requiresauth)
    <A HREF="<?= webroot('public/auth/login') . '?action=verify&code=' . $authcode; ?>">Click here to verify your email</A>
@endif

<?= view("email_test"); ?>