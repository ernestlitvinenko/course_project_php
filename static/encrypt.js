
const pass = document.querySelector('#password');
const form = document.querySelector('#reg_form');

const rsa = forge.pki.rsa;
const pki = forge.pki;

function encrypt(pwd) {
    const pub_key = pki.publicKeyFromPem(window.pub_key_pem)
    const encrypted = pub_key.encrypt(forge.util.encodeUtf8(pwd));
    return forge.util.encode64(encrypted)
}

form.addEventListener('submit', () => {
    pass.value = encrypt(pass.value)
})