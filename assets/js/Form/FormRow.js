'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormColumn from './FormColumn'

export default function FormRow(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template === false)
        return ''

    const columns = template.columns.map((column, key) => {
        return (
            <FormColumn
                key={key}
                template={column}
                {...otherProps}
            />
        )
    })

    return (
        <div className={template.class}>{columns}</div>
    )
}

FormRow.propTypes = {
    template: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.bool,
    ]).isRequired,
}
