function addFavoriteMovie(e) {
    e.preventDefault()

    const currentLink = this
    const url = currentLink.href
    const icon = currentLink.querySelector('i')
    let htmlText = this.querySelector('span')

    fetch(url)
        .then(response => response.json())
        .then(function (data) {
            if (data.isFavorited) {
                icon.classList.replace('far', 'fas')
                htmlText.innerHTML = 'Retirer des favoris'
            } else {
                icon.classList.replace('fas', 'far')
                htmlText.innerHTML = 'Ajouter en favoris'
            }
        })
        .catch(function (error) {
            console.log('Une erreur est survenue : ' + error)
        })
}

document.querySelectorAll('a.button-favorite').forEach(function (link) {
    link.addEventListener('click', addFavoriteMovie)
})