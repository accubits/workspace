import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormListingWrapComponent } from './form-listing-wrap.component';

describe('FormListingWrapComponent', () => {
  let component: FormListingWrapComponent;
  let fixture: ComponentFixture<FormListingWrapComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormListingWrapComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormListingWrapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
