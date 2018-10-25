'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function FormRequired(props) {
    const {
        required,
    } = props

    if (required === '')
        return {required}

    return (
        <span className="field-required">&nbsp;{required}</span>
    )
}

FormRequired.propTypes = {
    required: PropTypes.string.isRequired,
}
