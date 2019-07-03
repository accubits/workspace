import { TestBed, inject } from '@angular/core/testing';

import { PartnerManagerApiService } from './partner-manager-api.service';

describe('PartnerManagerApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PartnerManagerApiService]
    });
  });

  it('should be created', inject([PartnerManagerApiService], (service: PartnerManagerApiService) => {
    expect(service).toBeTruthy();
  }));
});
