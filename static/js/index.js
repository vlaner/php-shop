const cartAdd = async (clickInfo) => {
    const productId = parseInt(clickInfo.target.attributes['product-id'].value)

    const data = JSON.stringify({ product_id: productId })

    let response = await fetch('/ajax/add-to-cart.php', {
        method: 'POST',
        body: data,
    })

    const result = await response.json()

    if (result === -1) {
        clickInfo.target.text = 'Failed to add to cart'
        return
    }
    clickInfo.target.text = 'Added to cart'
    clickInfo.target.href = '/cart/show.php'
    clickInfo.target.removeEventListener('click', cartAdd)
}
const loadItems = async () => {
    const productName = document.getElementById('product-name')

    if (productName) {
        const data = JSON.stringify({ product_name: productName.innerHTML })
        var response = await fetch('/ajax/get-products.php', {
            method: 'POST',
            body: data,
        })
    } else {
        var response = await fetch('/ajax/get-products.php', {
            method: 'POST',
        })
    }

    const result = await response.json()
    if (result === -1) {
        document.getElementsByClassName('show-more')[0].remove()
        return
    }

    let row = document.getElementsByClassName('row')[0]
    result.forEach((element) => {
        const { price, title, photo, id } = element
        //
        let col = document.createElement('div')
        col.classList.add('col-md-4')

        //
        let cardPhoto = document.createElement('a')
        cardPhoto.href = 'product/show.php?id=' + id
        let image = document.createElement('img')
        image.src = photo
        cardPhoto.appendChild(image)

        let br = document.createElement('br')
        //
        let cardTitle = document.createElement('a')
        cardTitle.classList.add('link-dark')
        cardTitle.href = 'product/show.php?id=' + id
        let cardTitleText = document.createElement('span')
        cardTitleText.innerHTML = title
        cardTitle.appendChild(cardTitleText)

        //
        let cardPrice = document.createElement('div')
        cardPrice.innerHTML = price + ' ₽ '
        let addToCart = document.createElement('a')
        addToCart.innerHTML = ' To cart'
        addToCart.classList.add(
            ...['cart-add', 'btn', 'btn-outline-dark', 'me-2']
        )
        addToCart.setAttribute('product-id', id)
        addToCart.addEventListener('click', cartAdd)
        cardPrice.appendChild(addToCart)
        //

        document.body.appendChild(col)

        col.appendChild(cardPhoto)
        col.appendChild(br)
        col.appendChild(cardTitle)
        col.appendChild(cardPrice)

        row.append(col)
    })
}

try {
    const showMore = document.getElementById('show-more')
    showMore.addEventListener('click', loadItems)
} catch (error) {}

const cartAddBtns = document.getElementsByClassName('cart-add')

for (const btn of cartAddBtns) {
    if (btn.innerHTML != 'Added to cart') {
        btn.addEventListener('click', cartAdd)
    }
}
