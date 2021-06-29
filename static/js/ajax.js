const cartAdd = async (clickInfo) => {
    const productId = parseInt(clickInfo.target.attributes['product-id'].value)

    const data = JSON.stringify({ product_id: productId })

    let response = await fetch('/ajax/add-to-cart.php', {
        method: 'POST',
        body: data,
    })

    const result = JSON.parse(await response.json())

    if (result === -1) {
        clickInfo.explicitOriginalTarget.text = 'Failed to add to cart'
        return
    }
    clickInfo.explicitOriginalTarget.text = 'Added to cart'
    clickInfo.explicitOriginalTarget.href = '/cart/show.php'
    clickInfo.explicitOriginalTarget.removeEventListener('click', cartAdd)
}
const loadItems = async () => {
    const productName = document.getElementById('product-name')

    if (productName) {
        const data = JSON.stringify(productName.innerHTML)

        var response = await fetch('/ajax/get-products.php', {
            method: 'POST',
            body: data,
        })
    } else {
        var response = await fetch('/ajax/get-products.php', {
            method: 'POST',
        })
    }

    const result = JSON.parse(await response.json())
    if (result === -1) {
        document.getElementsByClassName('show-more')[0].remove()
        return
    }

    let container = document.getElementsByClassName('container')[0]
    result.forEach((element) => {
        const { price, title, photo, id } = element

        //
        let card = document.createElement('div')
        card.classList.add('card')

        //
        let cardTitle = document.createElement('a')
        cardTitle.classList.add('card__title')
        cardTitle.href = 'product/show.php?id=' + id
        let cardTitleText = document.createElement('span')
        cardTitleText.innerHTML = title
        cardTitle.appendChild(cardTitleText)

        //
        let cardPhoto = document.createElement('div')
        cardPhoto.classList.add('card__photo')
        let image = document.createElement('img')
        image.src = photo
        cardPhoto.appendChild(image)

        //
        let cardPrice = document.createElement('div')
        cardPrice.classList.add('card__price')
        cardPrice.innerHTML = price + ' ₽'
        let addToCart = document.createElement('a')
        addToCart.innerHTML = ' To cart'
        addToCart.classList.add('cart-add')
        addToCart.setAttribute('product-id', id)
        addToCart.addEventListener('click', cartAdd)
        cardPrice.appendChild(addToCart)
        //

        document.body.appendChild(card)

        card.appendChild(cardPhoto)
        card.appendChild(cardTitle)
        card.appendChild(cardPrice)

        container.append(card)
    })
}

try {
    const show_more = document.getElementById('show-more')
    show_more.addEventListener('click', loadItems)
} catch (error) {}

const cartAddBtns = document.getElementsByClassName('cart-add')

for (const btn of cartAddBtns) {
    if (btn.innerHTML != 'Added to cart') {
        btn.addEventListener('click', cartAdd)
    }
}
