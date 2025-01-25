function openShareModal(postId) {
    let postURL = 'http://localhost/Reppost/home.php?id=' + postId;
    // Facebook
    const fbShare   = document.getElementById('fbShareLink');
    fbShare.href    = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(postURL);
    // X (Twitter)
    const xShare    = document.getElementById('xShareLink');
    xShare.href     = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(postURL) + '&text=Mira+este+post';
    // WhatsApp
    const waShare   = document.getElementById('waShareLink');
    waShare.href    = 'https://api.whatsapp.com/send?text=' + encodeURIComponent('Mira este post: ' + postURL);
    // Reddit
    const rdShare   = document.getElementById('rdShareLink');
    rdShare.href    = 'https://www.reddit.com/submit?url=' + encodeURIComponent(postURL) + '&title=Mira+este+post';
    // Telegram
    const tgShare   = document.getElementById('tgShareLink');
    tgShare.href    = 'https://t.me/share/url?url=' + encodeURIComponent(postURL) + '&text=Mira+este+post';
    // Email (mailto)
    const mailShare = document.getElementById('mailShareLink');
    mailShare.href  = 'mailto:?subject=Mira este post&body=' + encodeURIComponent('Revisa esta publicación: ' + postURL);
    // Gmail (similar a mailto, pero abriendo Gmail en web)
    const gmailShare = document.getElementById('gmailShareLink');
    gmailShare.href  = 'https://mail.google.com/mail/?view=cm&fs=1&body=' + encodeURIComponent('Mira este post: ' + postURL) 
                        + '&su=' + encodeURIComponent('Publicación Interesante');
    // Messenger 
    const messengerLink = document.getElementById('messengerLink');
    messengerLink.href  = 'fb-messenger://share?link=' + encodeURIComponent(postURL);
    // Botón "Copiar enlace"
    const copyBtn = document.getElementById('copyLinkBtn');
    copyBtn.onclick = function() {
       navigator.clipboard.writeText(postURL).then(() => {
          alert('Enlace copiado al portapapeles!');
       }).catch(err => {
          console.error('Error al copiar enlace', err);
       });
    };
    $('#shareModal').modal('show');
}
