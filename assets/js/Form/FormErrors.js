'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faExclamationCircle } from '@fortawesome/free-solid-svg-icons'


export default function FormErrors(props) {
    const {
        errors
    } = props

    if (errors.length === 0)
        return ''

    const errorList = errors.map((error,key) => {
        return (
            <li key={key}><FontAwesomeIcon icon={faExclamationCircle}/> {error}</li>
        )
    })

    return (
        <small className={'text-danger form-text'}>
            <ul className="list-unstyled">
                {errorList}
            </ul>
        </small>
    )
}

FormErrors.propTypes = {
    errors: PropTypes.array,
}

FormErrors.defaultProps = {
    errors: [],
}
