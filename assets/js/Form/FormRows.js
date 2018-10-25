'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRow from './FormRow'

export default function FormRows(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template === false)
        return ''

    const rowContent = template.map((row, key) => {
        return (
            <FormRow
                template={row}
                key={key}
                {...otherProps}
            />
        )
    })

    return (
        <span>{rowContent}</span>
    )
}

FormRows.propTypes = {
    template: PropTypes.oneOfType([
        PropTypes.array,
        PropTypes.bool,
    ]).isRequired,
}

