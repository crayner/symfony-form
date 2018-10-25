'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { ControlLabel, FormControl } from 'react-bootstrap'

export default function FormLabel(props) {
    const {
        label,
    } = props

    if (label === false)
        return ('')

    return (
        <span>
            <ControlLabel>{label}</ControlLabel>
            <FormControl.Feedback />
        </span>
    )
}

FormLabel.propTypes = {
    label: PropTypes.oneOfType([
        PropTypes.bool,
        PropTypes.string,
    ]).isRequired,
}
