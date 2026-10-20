import * as contact from './modules/contact.js';
import * as cv from './modules/cv.js';
import * as lightbox from './modules/lightbox.js';
import * as menu from './modules/menu.js';
import * as project from './modules/project.js';
import * as team from './modules/team.js';
import * as worklist from './modules/worklist.js';

// Module scripts run after the document is parsed
[contact, cv, lightbox, menu, project, team, worklist].forEach((module) => module.init());
