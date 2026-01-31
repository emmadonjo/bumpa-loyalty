/// <reference types="cypress" />
// ***********************************************
// This example commands.ts shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
//
// declare global {
//   namespace Cypress {
//     interface Chainable {
//       login(email: string, password: string): Chainable<void>
//       drag(subject: string, options?: Partial<TypeOptions>): Chainable<Element>
//       dismiss(subject: string, options?: Partial<TypeOptions>): Chainable<Element>
//       visit(originalFn: CommandOriginalFn, url: string, options: Partial<VisitOptions>): Chainable<Element>
//     }
//   }
// }
export {};

declare global {
    // eslint-disable-next-line @typescript-eslint/no-namespace
    namespace Cypress {
        interface Chainable {
            logout(): Chainable<void>;
            login(role?: string, token?: string): Chainable<void>;
        }
    }
}

Cypress.Commands.add('login', (
    role: string = 'admin',
    token: string = '1|hsahsatjh21621813891281'
) => {
    cy.visit('/login');

    cy.window().then((win) => {
        win.localStorage.setItem('auth', token);

        const customerUser = {
            id: 2,
            name: 'Customer',
            email: 'customer@example.com',
            role: 'customer'
        }

        const adminUser = {
            id: 1,
            name: 'John Admin',
            email: 'admin@example.com',
            role: 'admin'
        };

        const user = role === 'admin' ? adminUser : customerUser

        win.localStorage.setItem('auth', token);
        win.localStorage.setItem('_user', JSON.stringify(user));

    });
    cy.window().its('localStorage._user').should('exist');
    cy.window().its('localStorage.auth').should('exist');
    cy.visit('/');
});

Cypress.Commands.add('logout', () => {
    cy.window().then((win) => {
        win.localStorage.removeItem('auth');
        win.localStorage.removeItem('_user');
    });
});