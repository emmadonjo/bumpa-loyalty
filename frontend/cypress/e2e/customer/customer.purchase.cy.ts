describe('Test Purchase Flow', () => {
    beforeEach(() => {
        cy.login('customer');
    });

    it("should display purchase button", function () {
        cy.visit('/');
        cy.get('#purchaseForm')
            .contains('button','Purchase').should('be.visible');
    });

    it('should redirect to Paystack checkout on success', function () {
        const checkoutUrl = 'https://checkout.paystack.com/216521276121';

        cy.intercept('POST', '**/api/users/purchases', {
            statusCode: 200,
            body: {
                status: true,
                data: {
                    checkout_url: checkoutUrl,
                    reference: '216521276128943',
                }
            }
        }).as('submitPurchase');

        cy.visit('/');
        cy.get('#purchaseForm').contains('button', 'Purchase').click();

        cy.wait('@submitPurchase');

        cy.origin('https://checkout.paystack.com', { args: { checkoutUrl } }, ({ checkoutUrl }) => {
            cy.url().should('include', checkoutUrl);
        });
    });

    it("should verify a payment", function () {
        const reference = "32681h1712t121";
        cy.visit(`/callbacks/payments/${reference}/verify`);

        cy.intercept('POST', `**/api/payments/${reference}/verify`, {
            statusCode: 200,
            body: {
                status: true,
                message: ""
            }
        }).as('verifyPurchase');

        cy.wait('@verifyPurchase');
        cy.visit('/');
    });

    it("should cancel a payment", function () {
        const reference = "32681h1712t121";
        cy.visit(`/callbacks/payments/${reference}/cancel`);

        cy.intercept('POST', `**/api/payments/${reference}/cancel`, {
            statusCode: 200,
            body: {
                status: true,
                message: ""
            }
        }).as('cancelPurchase');

        cy.wait('@cancelPurchase');
        cy.visit('/');
    })

})