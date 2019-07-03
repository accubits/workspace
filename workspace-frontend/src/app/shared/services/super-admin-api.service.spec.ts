import { TestBed, inject } from '@angular/core/testing';

import { SuperAdminApiService } from './super-admin-api.service';

describe('SuperAdminApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [SuperAdminApiService]
    });
  });

  it('should be created', inject([SuperAdminApiService], (service: SuperAdminApiService) => {
    expect(service).toBeTruthy();
  }));
});
