import {User} from "@/types";

describe("Admin Customer Management", () => {
    beforeEach(() => {
        cy.fixture('users').as('usersJson');
    });

    it('displays the list of customers with correct data', function() {
        cy.login('admin');

        cy.intercept('GET', '**/api/admin/users*', {
            statusCode: 200,
            body: {
                status: true,
                data: this.usersJson,
                meta: { current_page: 1, total: 10, last_page: 1 }
            }
        }).as('getUsers');

        cy.visit('/');
        cy.wait('@getUsers');

        cy.get('.customers-list table tbody tr').should('have.length', this.usersJson.length);

        this.usersJson.forEach((user: User) => {
            cy.contains('.customers-list table tr', user.email).should('be.visible');
        });
    });

    it('denies access to customers and renders dashboard instead', () => {
        cy.login('customer');
        cy.visit('/');

        cy.get('.customers-list table tbody').should('have.length', 0);
    });
});