'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { HelpBlock } from 'react-bootstrap'


export default function FormHelp(props) {
    const {
        help,
    } = props

    if (help === false || help === null || help === '')
        return ''

    return (
        <HelpBlock className={'text-muted form-text small'}>{help}</HelpBlock>
    )
}

FormHelp.propTypes = {
    help: PropTypes.string,
}

FormHelp.defaultProps = {
    help: ''
}
