import { TestBed, inject } from '@angular/core/testing';

import { TimeDateService } from './time-date.service';

describe('TimeDateService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TimeDateService]
    });
  });

  it('should be created', inject([TimeDateService], (service: TimeDateService) => {
    expect(service).toBeTruthy();
  }));
});
