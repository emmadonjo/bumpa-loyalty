Cypress.Commands.add('login', (token: string = '1|hsahsatjh21621813891281') => {
    cy.visit('/');
    window.localStorage.setItem('auth', token);
});

Cypress.Commands.add('logout', () => {
    cy.window().then((win) => {
        win.localStorage.removeItem('auth');
    });
});

describe('Authentication Flow', () => {
    it('should log in successfully and redirect', () => {
        cy.intercept('POST', '**/api/login', {
            statusCode: 200,
            fixture: 'login.json'
        }).as('getUser');

        cy.visit('/login');
        cy.get('input[name="email"]').type('customer@example.com');
        cy.get('input[name="password"]').type('password');
        cy.get('button[type="submit"]').click();

        cy.wait('@getUser');

        cy.window().should((win) => {
            expect(win.localStorage.getItem('auth')).to.be.a('string');
        });

        cy.url().should('match', /\/$/);
    });

    it('should protect the dashboard page', () => {
        cy.visit('/');
        cy.url().should('include', '/login');
    });

    it('should access dashboard when token is present', () => {
        cy.login();
        cy.visit('/');
        cy.contains('Bumpa Loyalty').should('be.visible');
        cy.contains('Dashboard').should('be.visible');
    });

    // it('should log out a user', () => {
    //     cy.login();
    //
    //     cy.intercept('POST', '**/api/logout', {
    //         statusCode: 200,
    //         body: {
    //             data: {
    //                 message: 'Logout successful',
    //                 status: true,
    //                 data: [],
    //             }
    //         }
    //     }).as('logoutUser');
    //
    //     cy.visit('/');
    //     cy.get('.user-menu').should('be.visible').click({force: true});
    //     cy.get('.logout').should('be.visible').click();
    //     cy.wait('@logoutUser');
    //
    //     cy.window().should((win) => {
    //         expect(win.localStorage.getItem('auth')).to.be.null;
    //     });
    //
    //     cy.url().should('include', '/login');
    // });
});