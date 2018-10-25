'use strict';

import React from 'react'
import { render } from 'react-dom'
import FormControl from './Form/FormControl'

render(
    <FormControl
        {...window.FORM_PROPS}
    />,
    document.getElementById('pageContent')
)
