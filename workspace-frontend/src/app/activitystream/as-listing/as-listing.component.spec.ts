import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsListingComponent } from './as-listing.component';

describe('AsListingComponent', () => {
  let component: AsListingComponent;
  let fixture: ComponentFixture<AsListingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsListingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsListingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
