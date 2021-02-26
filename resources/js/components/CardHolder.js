import React from 'react';
import ReactDOM from 'react-dom';
import { PaymentInputsWrapper, usePaymentInputs } from 'react-payment-inputs';
import images from 'react-payment-inputs/images';

const CardHolder = () => {
    const {
        wrapperProps,
        getCardImageProps,
        getCardNumberProps,
        getExpiryDateProps,
        getCVCProps
    } = usePaymentInputs();

    // Override the CSS to add the Bootstrap styles
    const styles = {
        inputWrapper: {
            focused : {
                borderColor : '#a1cbef',
                boxShadow : '0 0 0 0.2rem rgba(52, 144, 220, 0.25)'
            }
        }
    }

    return (
        <PaymentInputsWrapper styles={styles} {...wrapperProps}>
            <svg {...getCardImageProps({ images })} />
            <input required min="11" {...getCardNumberProps()} />
            <input required {...getExpiryDateProps()} />
            <input required min="3" {...getCVCProps()} />
        </PaymentInputsWrapper>
    )
}

if (document.getElementById('card-holder')) {
    ReactDOM.render(<CardHolder />, document.getElementById('card-holder'));
}
