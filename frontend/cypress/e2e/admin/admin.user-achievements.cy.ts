import {UserAchievement} from "@/types";

describe('Admin user achievements list', () => {
    beforeEach(() => {
        cy.fixture('user-achievements').as('userAchievements');
    });

    it('should display list of user achievements', function (){
        cy.login();

        cy.intercept('GET', '**/admin/users/achievements*', {
            statusCode: 200,
            body: {
                status: true,
                message: '',
                data: this.userAchievements,
                meta: {current_page: 1, total: 10, last_page: 1, per_page: 20},
            }
        }).as('getUserAchievements');

        cy.visit('/admin/achievements');
        cy.wait('@getUserAchievements');

        cy.get('.admin-user-achievements-list table tbody tr').should('have.length', this.userAchievements.length);

        this.userAchievements.forEach((userAchievement: UserAchievement) => {
            cy.contains('.admin-user-achievements-list table tbody tr', userAchievement.user.name).should('be.visible');
            cy.contains('.admin-user-achievements-list table tbody tr', userAchievement.achievement.name).should('be.visible');

            cy.contains('.admin-user-achievements-list table tbody', userAchievement.achievement.type.replace('_', ' '))
                .should('be.visible');
        });
    });

    it('should allow customer retrieve list of user achievements', () => {
        cy.login('customer');
        cy.visit('/admin/achievements');

        cy.intercept('GET', '**/admin/users/achievements*', {
            statusCode: 403,
            body: { message: 'Forbidden' }
        }).as('dontGetUserAchievements');
        cy.wait('@dontGetUserAchievements');

        cy.visit('/');
        cy.get('.admin-user-achievements-list table tbody tr').should('have.length', 0);
    });
})