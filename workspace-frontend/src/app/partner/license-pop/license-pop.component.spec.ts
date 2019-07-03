import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicensePopComponent } from './license-pop.component';

describe('LicensePopComponent', () => {
  let component: LicensePopComponent;
  let fixture: ComponentFixture<LicensePopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicensePopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicensePopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
