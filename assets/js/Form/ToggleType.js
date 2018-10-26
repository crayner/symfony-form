'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormLabel from './FormLabel'
import FormHelp from './FormHelp'
import FormErrors from './FormErrors'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FormGroup } from 'react-bootstrap'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'
import '../../css/toggle.scss'

library.add(fas, far, fab)

export default function ToggleType(props) {
    const {
        element,
        style,
        value,
        elementClick,
    } = props

    let buttonClass =  value === '1' ? element.button_class_on : element.button_class_off
    buttonClass= (buttonClass + ' ' + element.div_class).trim()

    let iconClass = value === '1' ? element.icon_class_on : element.icon_class_off
    iconClass = iconClass.split(' ')
    iconClass[1] = iconClass[1].replace('fa-', '')
    iconClass[0] = iconClass[0].replace('fa-', '')

    return (
        <FormGroup
            controlId={element.id}
            className={element.errors.length > 0 ? 'has-danger' : ''}
            key={element.id + value}
            >
            <button type={'button'} className={buttonClass} onClick={(e) => elementClick(e, element.id)} value={element.value}>
                <FontAwesomeIcon icon={iconClass} fixedWidth={true}/>
            </button>
            <FormLabel label={element.label}/>
            {style === 'row' ? <span>
                <FormErrors errors={element.errors}/>
                <FormHelp help={element.help}/>
            </span> : '' }
        </FormGroup>
    )
}

ToggleType.propTypes = {
    element: PropTypes.object.isRequired,
    style: PropTypes.string.isRequired,
    value: PropTypes.string.isRequired,
    elementClick: PropTypes.func.isRequired,
}

