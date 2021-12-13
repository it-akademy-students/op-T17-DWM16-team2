function addFavoriteMovie(e) {
    e.preventDefault()

    const currentLink = this
    const movieCard = this.closest('.small-movie')
    const url = currentLink.href
    const icon = currentLink.querySelector('i')
    let htmlText = ''

    if (this.querySelector('span')) htmlText = this.querySelector('span')

    fetch(url)
        .then(response => response.json())
        .then(function (data) {
            if (data.isFavorited) {
                icon.classList.replace('far', 'fas')
                if (htmlText) htmlText.innerHTML = 'Retirer des favoris'
            } else {
                if (document.URL.includes('account')) movieCard.remove()
                icon.classList.replace('fas', 'far')
                if (htmlText) htmlText.innerHTML = 'Ajouter en favoris'
            }
        })
        .catch(function (error) {
            console.log('Une erreur est survenue : ' + error)
        })
}

document.querySelectorAll('a.button-favorite').forEach(function (link) {
    link.addEventListener('click', addFavoriteMovie)
})