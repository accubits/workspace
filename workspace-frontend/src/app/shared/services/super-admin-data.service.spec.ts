import { TestBed, inject } from '@angular/core/testing';

import { SuperAdminDataService } from './super-admin-data.service';

describe('SuperAdminDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [SuperAdminDataService]
    });
  });

  it('should be created', inject([SuperAdminDataService], (service: SuperAdminDataService) => {
    expect(service).toBeTruthy();
  }));
});
