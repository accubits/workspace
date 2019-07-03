import { TestBed, inject } from '@angular/core/testing';

import { PartnerApiService } from './partner-api.service';

describe('PartnerApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PartnerApiService]
    });
  });

  it('should be created', inject([PartnerApiService], (service: PartnerApiService) => {
    expect(service).toBeTruthy();
  }));
});
