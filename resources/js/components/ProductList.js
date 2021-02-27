import React from 'react';
import ReactDOM from 'react-dom';
import {HTTP_OK} from "../util/consts";

const ProductList = ({products}) => {

    const onProductClickBtn = async (product) => {
        const response = await window.axios.post('/add-to-cart', {product});

        // If its ok we redirect to checkout
        // No need to handle the error as we redirect from controller on that
        if (response.status === HTTP_OK) {
            window.location.replace('/checkout');
        }

    }

    return (
        <div className="row justify-content-center">
            {products.map(product => {
                return (
                    <ProductItem
                        key={product.id}
                        id={product.id}
                        name={product.name}
                        price={product.price}
                        brand={product.brand.name}
                        onClick={onProductClickBtn}
                    />
                )
            })
            }
        </div>
    )

}


const ProductItem = ({id, name, price, brand, onClick}) => {

    const formatPrice = (price) => {
        const formatter = new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: "EUR"
        });
        return formatter.format(price);
    }
    return (
        <div className="col-6 col-sm-4 col-md-4 ">
            <div className="border rounded text-center m-2">
                <img width="100%" alt="random-image" src="https://picsum.photos/seed/picsum/300/300" />
                <div className="p-2">
                    <h3 className="mt-2 font-weight-bolder text-uppercase">{name}</h3>
                    <small>{brand}</small>
                    <p className="font-italic font-weight-bold m-2">{formatPrice(price)}</p>
                    <button onClick={() => onClick(id)} className="btn btn-primary buy-btn">BUY</button>
                </div>

            </div>

        </div>
    )
}


if (document.getElementById('product-list')) {
    // get the list from data-products attr
    const products = JSON.parse(document.getElementById("product-list").dataset.products) || [];
    ReactDOM.render(<ProductList products={products}/>, document.getElementById('product-list'));
}
