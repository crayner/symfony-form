'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormTypes from './FormTypes'

export default function FormElementSelect(props) {
    const {
        style,
        name,
        form,
        ...otherProps
    } = props

    const element = form.children.find(child => {
        if (child.name === name)
            return child
    })

    return (
        <FormTypes
            form={{...element}}
            style={style}
            {...otherProps}
        />
    )
}

FormElementSelect.propTypes = {
    style: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
    form: PropTypes.object.isRequired,
}
