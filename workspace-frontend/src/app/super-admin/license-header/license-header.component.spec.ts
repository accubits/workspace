import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseHeaderComponent } from './license-header.component';

describe('LicenseHeaderComponent', () => {
  let component: LicenseHeaderComponent;
  let fixture: ComponentFixture<LicenseHeaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseHeaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseHeaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
