import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormListingNavComponent } from './form-listing-nav.component';

describe('FormListingNavComponent', () => {
  let component: FormListingNavComponent;
  let fixture: ComponentFixture<FormListingNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormListingNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormListingNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
