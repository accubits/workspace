import { TestBed, inject } from '@angular/core/testing';

import { HrmApiService } from './hrm-api.service';

describe('HrmApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [HrmApiService]
    });
  });

  it('should be created', inject([HrmApiService], (service: HrmApiService) => {
    expect(service).toBeTruthy();
  }));
});
