import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateLicenseComponent } from './create-license.component';

describe('CreateLicenseComponent', () => {
  let component: CreateLicenseComponent;
  let fixture: ComponentFixture<CreateLicenseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateLicenseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateLicenseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
